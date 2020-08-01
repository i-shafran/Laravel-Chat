<template>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-sm-3">
                <u>
                    <li><a href="/room/1">The first room</a></li>
                    <li><a href="/room/2">The second room</a></li>
                    <li><a href="/room/3">The last room</a></li>
                </u>
            </div>
            <div class="col-sm-6">
                <textarea class="form-control" rows="10" readonly>{{messages.join('\n')}}</textarea>
                <hr>
                <input type="text" class="form-control" v-model="textMessage" @keyup.enter="sendMessage" @keydown="actionUser">
                <span v-if="isActive">{{isActive.name}} набирает сообщение...</span>
            </div>
            <div class="col-sm-3">
                <ul>
                    <li v-for="user in activeUsers">{{user}}</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
		name: "private-chat",
        computed: {
		    chanel: function() {
		    	return window.Echo.join('room.' + this.room.id);
            }	
        },
        mounted() {
			this.setAllMessages();
			this.chanel
                .here((users) => {
                	this.activeUsers = users;
                })
                .joining((user) => {
					this.activeUsers.push(user);
                })
                .leaving((user) => {
					this.activeUsers.splice(this.activeUsers.indexOf(user), 1);
                })
                .listen('PrivateChatEvent', ({data}) => {
				    this.messages.push(data.body);
					this.isActive = false;
                })
                .listenForWhisper('typing', (event) => {
                	this.isActive = event;
                	
                	if (this.typingTimer) {
                		clearTimeout(this.typingTimer)
                    }
					this.typingTimer = setTimeout(() => {
                		this.isActive = false;
                    }, 2000);
                });
        },
        data() {
			return {
				messages: [],
                textMessage: '',
				isActive: false,
                typingTimer: false,
				activeUsers: []
            }
        },
        methods: {
            // Messages from DB
			setAllMessages() {
				for (let index in this.db_messages) {
					this.messages.push(this.db_messages[index].message);
                } 
            },
			sendMessage() {
				axios.post('/private_messages', {body: this.textMessage, room_id: this.room.id});
				
				this.messages.push(this.textMessage);
				this.textMessage = '';
            },
			actionUser() {
				this.chanel.whisper('typing', {
					name: this.user.name
                })
            }
        },
        props: ['room', 'user', 'db_messages']
	}
</script>

<style scoped>

</style>