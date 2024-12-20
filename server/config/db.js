import { MongoClient } from "mongodb";
const url = process.env.MONGOURL || "mongodb://localhost:27017/chat_messages";

const connectDB = async () => {
  try {
    const client = await  MongoClient.connect(url);
    console.log("Connected to MongoDB");
    return client;
  } catch (err) {
    console.error("Error connecting to mongoDb", err);
  }
};


export const initMongoDB = async () => {
  const conn = await connectDB();
  const databaseName = conn.db("chat_message");
  return databaseName;
};


export const initCollection = async (databaseName) => {
  const collection = databaseName.collection("message");
  return collection;
};


export default connectDB;
