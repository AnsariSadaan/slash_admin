import { initCollection, initMongoDB } from "../config/db.js";


const sendMessage = async (req, res) => {
    const { sender, receiver, message } = req.body;
    try {
        const databaseName = await initMongoDB();
        const messageCollection = await initCollection(databaseName);
        // Create a timestamp
        const timestamp = new Date();
        // Check if a document for this pair of users exists
        const newMessage = {
            participants: [sender, receiver],
            sender,
            receiver,
            message,
            timestamp,
        };

        const result = await messageCollection.insertOne(newMessage);

        if (!result.acknowledged) {
            return res.status(400).json({ message: 'Error sending message' });
        }
        return res.status(200).json({ message: 'Message sent successfully' });
    } catch (err) {
        console.log(err);
        return res.status(500).json({ message: 'Internal Server Error' });
    }
};

export const getMessage = async (req, res) => {
    const { sender, receiver } = req.body;
    try {
        const databaseName = await initMongoDB();
        const messageCollection = await initCollection(databaseName);

        // fetch all message between sender and receiver
        const message = await messageCollection.find({
            participants: {$all: [sender, receiver]},
        }).sort({timestamp: 1}).toArray();
        

        if (!message.length) {
            return res.status(200).json({ messages: [] }); // No messages yet
        }

        return res.status(200).json({ messages: message });
    } catch (err) {
        console.log(err);
        return res.status(500).json({ message: 'Internal Server Error' });
    }
};


export default sendMessage;