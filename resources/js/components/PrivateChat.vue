<template>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <textarea class="form-control" rows="10" readonly>{{messages.join('\n')}}</textarea>
                <hr>
                <input type="text" class="form-control" v-model="textMessage" @keyup.enter="sendMessage" @keydown="actionUser">
                <span v-if="isActive">{{isActive.name}} набирает сообщение...</span>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
		name: "private-chat",
        computed: {
		    chanel: function() {
		    	return window.Echo.private('room.' + this.room.id);
            }	
        },
        mounted() {
			this.chanel
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
                    }, 10000);
                });
        },
        data() {
			return {
				messages: [],
                textMessage: '',
				isActive: false,
                typingTimer: false,
            }
        },
        methods: {
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
        props: ['room', 'user']
	}
</script>

<style scoped>

</style>