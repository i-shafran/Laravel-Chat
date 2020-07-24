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
		name: "chat",
        mounted() {
			window.Echo.channel('chat').listen('MessageEvent', ({message}) => {
				this.messages.push(message);
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
				axios.post('/messages', {body: this.textMessage});
				
				this.messages.push(this.textMessage);
				this.textMessage = '';
            }
        }
	}
</script>

<style scoped>

</style>