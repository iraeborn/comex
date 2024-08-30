<template>
    <div class="pill-center" v-if="pages.length > 1">
        <ul class="pagination">
          <li class="page-item" v-if="this.$data.current_page - 1 >= 0"><a class="page-link" @click="PrevPage">Previous</a></li>
          <li class="page-item" :class="index == current_page ? 'active' : ''" v-for="(page, index) in pages"><a class="page-link" @click="SetPage(page, index)">{{index + 1}}</a></li>
          <li class="page-item" v-if="this.$data.current_page + 1 < this.$data.pages.length"><a class="page-link" @click="NextPage">Next</a></li>
        </ul>
    </div>
</template>

<script>
export default {
  name: 'popcorn-pagination',
  props: ['items'],
  data () {
    return {
        limit: 50,
        current_page: 0,
        pages: []
    }
  },
  mounted: function () {
  },
  watch: {
    items: function (newValue, oldValue) {
        this.$data.pages = []
        
        if(!newValue) return

        for (var i = 0; i < this.$props.items.length; i+= this.$data.limit) {
            this.$data.pages.push(this.$props.items.slice(i, i+this.$data.limit))
        }

        this.SetPage(this.$data.pages[0], 0)
    }
  },
  methods: {
    SetPage: function (page, index) {
        this.$emit('input', page)
        this.$data.current_page = index
    },
    NextPage: function () {
        var next_page = this.$data.current_page + 1

        if(next_page >= this.$data.pages.length) return

        this.SetPage(this.$data.pages[next_page], next_page)
    },
    PrevPage: function () {
        var prev_page = this.$data.current_page - 1

        if(prev_page < 0) return

        this.SetPage(this.$data.pages[prev_page], prev_page)
    },
  }
}
</script>