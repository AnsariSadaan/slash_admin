import express, { urlencoded, json } from "express";
import cors from "cors";
import { Server } from "socket.io";
import connectDB from "./config/db.js";
import dotenv from "dotenv";
import router from "./routes/routes.js";
import Redis from "ioredis";

dotenv.config();

const app = express();
app.use(cors({ origin: process.env.CORS, credentials: true }));
app.use(urlencoded({ extended: true }));
app.use(json());

app.use("/api", router);
const redis = new Redis(process.env.REDIS_URL);

const startServer = async () => {
  try {
    await connectDB(); // Ensures DB connection before starting server
    const server = app.listen(process.env.PORT, () => {
      console.log("Server is running on port", process.env.PORT);
    });

    const io = new Server(server, {
      cors: {
        origin: process.env.CORS,
        credentials: true,
      },
    });

    const redisSubscriber = new Redis(process.env.REDIS_URL);

    // Subscribe to the Redis "messages" channel
    redisSubscriber.subscribe("messages", (err) => {
      if (err) {
        console.error("Failed to subscribe to messages channel:", err);
      }
    });

    redisSubscriber.on("message", (channel, message) => {
      if (channel === "messages") {
        const parsedMessage = JSON.parse(message);
        const { receiver } = parsedMessage;

        // Emit the message to the receiver's room
        io.to(receiver).emit("receive_message", parsedMessage);
      }
    });

    io.on("connection", (socket) => {
      console.log("A user connected:", socket.id);

      // Handle user joining a specific room
      socket.on("join", ({ email }) => {
        console.log(`${email} joined their room.`);
        socket.join(email); // Join a room named after the user's email or ID
      });

      // Handle message sending
      socket.on("send_message", async (data, callback) => {
        const { sender_name, receiver_name, sender, receiver, message } = data;

        // Emit the message to the receiver's room
        const messageData = {
          sender_name,
          receiver_name,
          sender,
          receiver,
          message,
          timestamp: new Date(),
        };

        io.to(receiver).emit("receive_message", messageData); // Send to receiver
        redis.publish("messages", JSON.stringify(messageData)); // Publish to Redis
        callback({ status: "sent" }); // Notify sender
      });

      // Handle user disconnection
      socket.on("disconnect", () => {
        console.log("A user disconnected:", socket.id);
      });
    });
  } catch (error) {
    console.error("Error starting server:", error.message);
    process.exit(1);
  }
};

startServer();
