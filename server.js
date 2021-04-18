const { connect } = require('node:http2');

var app=require('express')()
var http=require('http').createServer(app)
var io= require('socket.io')(http)
app.get('/debateChat',(req,res)=>{
        res.sendFile(__dirname + '/debateChat.html');
})

io.on('connection',(socket) => {

        console.log('user on')

        socket.on("codeboard-message",(msg)=>{
                console.log("Message received :" + msg);
                socket.broadcast.emit("codeboard-message-broadcasted", msg)
        })
})


var server_port = process.env.PORT || 5500
http.listen(server_port)