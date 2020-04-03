<template>
  <div class="q-pa-md">
    <div class="row justify-center">
      <div class="col-12 col-md-6">
        <div class="q-pa-md">
          <q-input type="text" label="websocket地址" v-model="ws">
            <template v-slot:after>
              <q-btn
                round
                flat
                :icon="connected ? 'power' : 'send'"
                @click="connect"
                style="margin-right:10px"
              />
            </template>
          </q-input>
        </div>

        <div class="q-pa-md">
          <div class="column" style="height: 450px">
            <div class="col">
              <q-card class="text-center" style="width:320px;height:100%;margin:0 auto">
                <q-chip label="玩家3" />
              </q-card>
            </div>
            <div class="col-8">
              <div class="row" style="height:100%">
                <div class="col-3 self-center">
                  <q-card class="text-center" style="width:100%;height:120px;">
                    <q-chip label="玩家2" />
                  </q-card>
                </div>
                <div class="col-6" style="border:1px solid #ddd">
                  <q-scroll-area style="height: 100%;" id="msg_box">
                    <template v-for="(msg, key) in msgs">
                      <p :key="key">
                        <q-chip>{{msg.type}}</q-chip>
                        <b>[{{msg.time}}]</b>
                        {{msg.content}}
                      </p>
                    </template>
                  </q-scroll-area>
                </div>
                <div class="col-3 self-center">
                  <q-card class="text-center" style="width:100%;height:120px;">
                    <q-chip label="玩家4" />
                  </q-card>
                </div>
              </div>
            </div>
            <div class="col">
              <q-card class="text-center" style="width:320px;height:100%;margin:0 auto">
                <q-chip label="玩家1" />
              </q-card>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// or with import syntax
import io from 'socket.io-client'
export default {
  name: 'PageIndex',
  data () {
    return {
      ws: 'ws://192.168.56.66:9502',
      connected: false,
      socket: null,
      msgs: [
        {
          type: '系统',
          time: '09:02:15',
          content: '这是系统的测试信息'
        }
      ]
    }
  },
  methods: {
    connect () {
      if (this.connected) {
        this.socket.close()
        this.socket = null
        this.connected = false
      } else {
        this.socket = io(this.ws)
        this.socket.on('connection', (res) => {
          console.log(res)
        })
        console.log(this.socket, this.ws)
        this.connected = true
      }
    }
  }
}
</script>
