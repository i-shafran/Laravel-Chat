<template>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-sm-12">
                <textarea class="form-control" rows="10" readonly>{{messages.join('\n')}}</textarea>
                <hr>
                <input type="text" class="form-control" v-model="textMessage" @keyup.enter="sendMessage"/>
            </div>
        </div>
    </div>
</template>

<script>
	export default {
		name: "private-chat",
        mounted() {
			window.Echo.private('room.2').listen('PrivateChatEvent', ({data}) => {
				this.messages.push(data.body);
            });
        },
        data() {
			return {
				messages: [],
                textMessage: ''
            }
        },
        methods: {
			sendMessage() {
				axios.post('/private_messages', {body: this.textMessage, room_id: 2});
				
				this.messages.push(this.textMessage);
				this.textMessage = '';
            }
        }
	}
</script>

<style scoped>

</style>