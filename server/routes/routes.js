import express from 'express';
import sendMessage, { getMessage } from '../controller/sendMsgController.js';
const router = express.Router();


router.post('/send-message', sendMessage);
router.post('/get-message', getMessage);


export default router