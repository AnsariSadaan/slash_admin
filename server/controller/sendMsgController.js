import { initCollection, initMongoDB } from "../config/db.js";
import { messageQueue } from "../config/queue.js";
import Redis from 'ioredis';

const redis = new Redis(process.env.REDIS_URL);


const sendMessage = async (req, res) => {
    const { sender_name, receiver_name, sender, receiver, message } = req.body;

    try {
        // Enqueue the message as a job
        await messageQueue.add('storeMessage', {
            sender_name,
            receiver_name,
            sender,
            receiver,
            message,
            timestamp: new Date(),
        });

        return res.status(200).json({ message: 'Message queued successfully' });
    } catch (err) {
        console.error(err);
        return res.status(500).json({ message: 'Internal Server Error' });
    }
};


export const getMessage = async (req, res) => {
    const { sender, receiver } = req.body;

    try {
        const cacheKey = `messages:${sender}:${receiver}`;
        const cachedMessages = await redis.get(cacheKey);
        if (cachedMessages) {
            return res.status(200).json({ messages: JSON.parse(cachedMessages) });
        }

        const database = await initMongoDB();
        const messageCollection = await initCollection(database);

        const messages = await messageCollection
            .find({ participants: { $all: [sender, receiver] } })
            .sort({ timestamp: 1 })
            .toArray();

        if (messages.length > 0) {
            await redis.set(cacheKey, JSON.stringify(messages), 'EX', 60 * 1); // Cache for 5 minutes
        }

        return res.status(200).json({ messages });
    } catch (err) {
        console.error(err);
        return res.status(500).json({ message: 'Internal Server Error' });
    }
};


export default sendMessage;