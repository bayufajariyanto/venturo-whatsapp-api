const { Client, MessageMedia } = require('whatsapp-web.js');
const express = require('express');
const { body, validationResult } = require('express-validator');
const socketIO = require('socket.io');
const qrcode = require('qrcode');
const http = require('http');
const fs = require('fs');
const { phoneNumberFormatter } = require('./helpers/formatter');
// const fileUpload = require('express-fileupload');
const axios = require('axios');
const port = process.env.PORT || 3000;
process.setMaxListeners(0)

const app = express();
const server = http.createServer(app);
const io = socketIO(server);

app.use(express.json());
app.use(express.urlencoded({
  extended: true
}));
// app.use(fileUpload({
//   debug: true
// }));

const SESSION_FILE_PATH = './whatsapp-session.json';
let sessionCfg;
if (fs.existsSync(SESSION_FILE_PATH)) {
  sessionCfg = require(SESSION_FILE_PATH);
}

app.get('/', (req, res) => {
  // console.log(res)
  res.sendFile('index.html', {
    root: __dirname
  });
});

const client = new Client({
  restartOnAuthFail: true,
  puppeteer: {
    headless: true,
    args: [
      '--no-sandbox',
      '--disable-setuid-sandbox',
      '--disable-dev-shm-usage',
      '--disable-accelerated-2d-canvas',
      '--no-first-run',
      '--no-zygote',
      '--single-process', // <- this one doesn't works in Windows
      '--disable-gpu'
    ],
  },
  session: sessionCfg
});
// const client = new Client()

client.initialize();

// Socket IO
io.on('connection', function (socket) {
  socket.emit('message', 'Menghubungkan...');

  client.on('qr', (qr) => {
    qrcode.toDataURL(qr, (err, url) => {
      console.log('KODE QR TELAH DITERIMA', url);
      socket.emit('qr', url);
      socket.emit('message', "Kode QR telah diterima, silakan scan");
    });
  });

  client.on('ready', () => {
    socket.emit('ready', 'WhatsApp siap digunakan');
    socket.emit('message', 'WhatsApp siap digunakan');
  });

  client.on('authenticated', (session) => {
    socket.emit('authenticated', 'WhatsApp diautentikasi!');
    socket.emit('message', 'WhatsApp diautentikasi!');
    console.log('AUTHENTICATED', session);
    sessionCfg = session;
    fs.writeFile(SESSION_FILE_PATH, JSON.stringify(session), function (err) {
      if (err) {
        console.error(err);
      }
    });
  });

  client.on('auth_failure', function (session) {
    socket.emit('message', 'Autentikasi gagal, mulai ulang...');
  });

  client.on('disconnected', (reason) => {
    socket.emit('message', 'WhatsApp Terputus!');
    fs.unlinkSync(SESSION_FILE_PATH, function (err) {
      if (err) return console.log(err);
      console.log('File sesi dihapus!');
    });
    client.destroy();
    client.initialize();
  });
});
client.on('change_battery', (batteryInfo) => {
  // Battery percentage for attached device has changed
  const { battery, plugged } = batteryInfo;
  console.log(`Battery: ${battery}% - Charging? ${plugged}`);
});


// Cek nomor
function splitLines(t) { return t.split(/\r\n|\r|\n/); }
app.post('/cek-nomor', [
  body('nomor').notEmpty()
], async (req, res) => {
  let request = splitLines(req.body.nomor)
  let hasil = []
  var nomor, status, nomor2, keterangan

  console.log('---------')
  for (let i = 0; i < request.length; i++) {
    nomor = phoneNumberFormatter(request[i])
    nomor2 = nomor.split("@c.us")
    nomor2 = nomor2[0]
    status = await client.isRegisteredUser(nomor);
    keterangan = status == true ? 'Nomor terdaftar' : 'Nomor tidak terdaftar'
    hasil.push({ status: status, nomor: nomor2, keterangan: keterangan })
  }
  console.log(hasil)
  console.log('---------')
  return res.status(200).json({
    status: 200,
    hasil: hasil
  });
});

// Kirim pesan
// app.post('/kirim-pesan',[

// ], () => {
//   console.log('kirim pesan')
// })
server.listen(port, () => {
  console.log('Aplikasi berjalan pada port: ' + port);
});