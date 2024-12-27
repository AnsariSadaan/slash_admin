import express, { urlencoded, json } from "express";
import cors from "cors";
import { Server } from "socket.io";
import connectDB from "./config/db.js";
import dotenv from "dotenv";
import router from "./routes/routes.js";

dotenv.config();

const app = express();
app.use(cors({ origin: process.env.CORS, credentials: true }));
app.use(urlencoded({ extended: true }));
app.use(json());

app.use("/api", router);

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

    io.on("connection", (socket) => {
      socket.on("join",  (data)=> {
          console.log(`${data.email} joined`);
          socket.join(data.email);
      });
  
      socket.on("send_message", (data) => {
          const { sender, receiver, message } = data;
          // Emit message to the receiver
          io.to(receiver).emit("receive_message", { sender, message, timestamp: new Date() });
      });
  });

    // io.on("connection", (socket) => {
    //   console.log("New client connected:", socket.id);
    //   socket.on("join", (data) => {
    //     if (data?.data) {
    //       console.log("Joining room:", data.data);
    //       socket.join(data.data);
    //     } else {
    //       console.error("Invalid join data:", data);
    //     }
    //   });
    //   // Broadcast message to all connected clients
    //   socket.on("send_message", (data) => {
    //     if (data?.message) {
    //       console.log("Broadcasting message:", data.message);
    //       io.emit("receive_message", data.message); // This sends the message to all connected clients
    //     } else {
    //       console.error("Invalid message data:", data);
    //     }
    //   });
    // });
  } catch (error) {
    console.error("Error starting server:", error.message);
    process.exit(1);
  }
};

startServer();
