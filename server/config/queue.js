import { Queue } from "bullmq";
import dotenv from 'dotenv';

dotenv.config();

const connection = {
    connection: {
        host: process.env.REDIS_HOST,
        port: process.env.REDIS_PORT,
    }
}

export const messageQueue = new Queue('messageQueue', { connection });