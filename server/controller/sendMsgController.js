import { initCollection, initMongoDB } from "../config/db.js";


const sendMessage = async (req, res) => {
    const { name ,email, message } = req.body;
    try {
        const databaseName = await initMongoDB();
        const messageCollection = await initCollection(databaseName);
        
        // Create a timestamp
        const timestamp = new Date(); // Current date and time
        
        // Insert the message along with the timestamp
        const result = await messageCollection.insertOne({ name, email, message, timestamp });
        
        if (!result.acknowledged) {
            return res.status(400).json({ message: 'Error sending message' });
        }
        
        return res.status(200).json({ message: 'Message sent successfully' });
        
    } catch (err) {
        console.log(err);
        return res.status(500).json({ message: "Internal Server Error" });
    }
};

export const getMessage = async (req, res) => {
    const { from } = req.body;
    try {
        const databaseName = await initMongoDB();
        const messageCollection = await initCollection(databaseName);
        
        
        const result = await messageCollection.find({ sender: from }).toArray();
        
        return res.status(200).json({ messages: result });
    } catch (err) {
        console.log(err);
        return res.status(500).json({ message: "Internal Server Error" });
    }
}


export default sendMessage;