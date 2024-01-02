const qrcode = require('qrcode-terminal');
const axios = require('axios');

const { Client, LocalAuth } = require('whatsapp-web.js');

const client = new Client({
  authStrategy: new LocalAuth(),
  puppeteer: {
    // args: ['--proxy-server=proxy-server-that-requires-authentication.example.com'],
    headless: true
  }
});


const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const port = 3000;

client.on('qr', qr => {
  qrcode.generate(qr, { small: true });
});

client.initialize();

client.on('ready', () => {
  console.log('Client is ready!');
});

client.on('disconnected', (reason) => {
  console.log('Client was logged out', reason);
});

client.on('loading_screen', (percent, message) => {
  console.log('LOADING SCREEN', percent, message);
});

let rejectCalls = true;

client.on('call', async (call) => {
  console.log('Call received, rejecting.', call);
  if (rejectCalls) await call.reject();
  await client.sendMessage(call.from, `[${call.fromMe ? 'Outgoing' : 'Incoming'}] Phone call from ${call.from}, type ${call.isGroup ? 'group' : ''} ${call.isVideo ? 'video' : 'audio'} call. ${rejectCalls ? 'This call was automatically rejected by the script.' : ''}`);
});

client.on('message', async (message) => {
  const prefix = '/';
  if (message.body.startsWith(prefix)) {
    console.log(`Received message with prefix "${prefix}": ${message.body}`);
    
    // Make an HTTP POST request using Axios
    try {
      const response = await axios.post('http://192.168.137.1/api/wangsaf', {
        receivedMessage: message.body,
        sender: message.from,
        // Add any other data you want to send in the POST request
      });
      
      console.log('Message forwarded successfully:', response.data);
    } catch (error) {
      console.error('Error forwarding message:', error);
    }
  }
});

app.use(bodyParser.json());

app.post('/send-msg', (req, res) => {

  let number = req.body.number;
  if (number.startsWith("08")) {
    number = "628" + number.substring(2);
  }
  number = number.includes('@c.us') ? number : `${number}@c.us`;
  client.sendMessage(number, req.body.msg);

  res.send({
    message: 'Sending message to : ' + number,
  });
});

app.listen(port, () => {
  console.log(`cli-nodejs-api listening at http://localhost:${port}`)
});