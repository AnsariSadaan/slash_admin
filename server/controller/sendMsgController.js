import { initCollection, initMongoDB } from "../config/db.js";


const sendMessage = async (req, res) => {
    const { sender, receiver, message } = req.body;
    try {
        const databaseName = await initMongoDB();
        const messageCollection = await initCollection(databaseName);
        // Create a timestamp
        const timestamp = new Date();
        // Check if a document for this pair of users exists
        const conversation = await messageCollection.findOne({
            participants: { $all: [sender, receiver] },
        });

        if (conversation) {
            const result = await messageCollection.updateOne(
                { _id: conversation._id },
                {
                    $push: {
                        messages: { sender, receiver, message, timestamp },
                    },
                }
            );
            if (!result.acknowledged) {
                return res.status(400).json({ message: 'Error sending message' });
            }
        } else {
            // Create a new conversation document
            const result = await messageCollection.insertOne({
                participants: [sender, receiver],
                messages: [{ sender, receiver, message, timestamp }],
            });
            if (!result.acknowledged) {
                return res.status(400).json({ message: 'Error creating conversation' });
            }
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

        // Fetch the conversation between sender and receiver
        const conversation = await messageCollection.findOne({
            participants: { $all: [sender, receiver] },
        });

        if (!conversation) {
            return res.status(200).json({ messages: [] }); // No messages yet
        }

        return res.status(200).json({ messages: conversation.messages });
    } catch (err) {
        console.log(err);
        return res.status(500).json({ message: 'Internal Server Error' });
    }
};


export default sendMessage;