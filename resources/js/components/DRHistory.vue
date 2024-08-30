<template>
    <div class="history">
        <strong>Notes</strong>
        <div class="board">
            <div class="message" v-for="message in history" :class="message.me ? 'right' : 'left'">
                <div class="avatar">
                    <img v-if="message.avatar !== '/img/avatar.png'" :src="message.avatar" :alt="message.name">
                    <i v-else class="nav-icon icon-user"></i>
                </div>

                <div class="hystory-space"></div>

                <div class="message_content">
                    <p>{{ message.text }}</p>
                    <small class="author">{{ message.name }} - <span class="time">{{ message.created_at }}</span></small>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="input-area">
            <div class="input-group mb-3">
              <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1" v-model="text">
              <div class="input-group-append">
                <button class="btn btn-success" type="button" @click="Post">Send</button>
              </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
.history {
    margin-top: 20px;
}
.board {
    margin-top: 20px;
    height: 300px;
    overflow-y: scroll;
}

.message {
    background-color: #ececec;
    border-radius: 5px;
    display: flex;
    align-items: flex-stretch;
    padding: 5px 10px;
}

.message:not(:last-child) {
    margin-bottom: 10px;
}

.avatar {
    width: 75px;
    height: 75px;
    border: 1px solid #888;
    float: left;
    padding: 5px;
    background-color: #fff;
}

.avatar img {
    max-width: 100%;
    display: block;
}

.message_content {
    float: left;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 75px;
    width: 100%;
    text-align: justify;
}

.time {
    color: #9e9e9e;
}

p {
    margin-bottom: 0;
}

.right {
    flex-direction: row-reverse;
    background-color: #cdf3cd;
}

.right .author {
    text-align: right;
}

.input-area {
    margin-top: 20px;
}

.hystory-space {
    width: 10px;
}
</style>

<script>
export default {
    props: ['url'],
    name: 'dr-history',
    data () {
        return {
            text: null,
            history: [],
        }
    },
    watch: {
        url: function ( newValue, oldValue ) {
            this.$http.get(newValue).then( e => {
                this.$data.history = e.body
            })
        },
    },
    mounted: function () {
        if (!this.$props.url) return
        this.$http.get(this.$props.url).then( e => {
            this.$data.history = e.body
        })

    },
    updated: function () {
        var container = this.$el.querySelector(".board");
        container.scrollTop = container.scrollHeight;
    },
    methods: {
        Post: function () {  
            let text = this.$data.text

            if (!text) {
                this.$toaster.error('I need a text to send');
                return;
            }

            let message = {
                text,
            }

            this.$http.post(this.$props.url, message).then( e => {
                if(e.body.success) {
                    this.$data.text = null
                    this.$data.history.push(e.body.message)
                }

            })

        }
    }
}
</script>