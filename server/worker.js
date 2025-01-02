import { Worker } from "bullmq";
import { initCollection, initMongoDB } from "./config/db.js";

const connection = {
  connection: {
    host: process.env.REDIS_HOST,
    port: process.env.REDIS_PORT,
  },
};

const worker = new Worker(
  "messageQueue",
  async (job) => {
    const { sender_name, receiver_name, sender, receiver, message, timestamp } =
      job.data;
    try {
      const databaseName = await initMongoDB();
      const messageCollection = await initCollection(databaseName);

      const newMessage = {
        participants: [sender, receiver],
        sender_name,
        receiver_name,
        message,
        timestamp,
      };

      await messageCollection.insertOne(newMessage);
      console.log("Message stored in MongoDB:", newMessage);
    } catch (error) {
      console.error("Error storing message:", error);
    }
  },
  connection
);

worker.on("completed", (job) => {
  console.log(`Job ${job.id} completed`);
});

worker.on("failed", (job, err) => {
  console.error(`Job ${job.id} failed:`, err);
});
