import { MongoClient } from "mongodb";
// const url = process.env.MONGOURL || "mongodb+srv://admin:admin@cluster0.fxkbz.mongodb.net/chat_message";
const url = process.env.MONGOURL || "mongodb://localhost:27017/chat_message";

let client;

const connectDB = async () => {
  try {
    if (!url) throw new Error("MongoDB connection URL is not defined");
    if (!client) {
      client = await MongoClient.connect(url);
      console.log("Connected to MongoDB");
    }
    return client;
  } catch (err) {
    console.error("Error connecting to MongoDB:", err.message);
    process.exit(1);
  }
};

export const initMongoDB = async () => {
  const client = await connectDB();
  const database = client.db("chat_message");
  return database;
};

export const initCollection = async (database) => {
  if (!database) throw new Error("Database instance is not provided");
  const collection = database.collection("message");
  return collection;
};

export default connectDB;
