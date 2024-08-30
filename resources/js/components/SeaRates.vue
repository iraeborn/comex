<template>
  <div>
    <iframe src="/api/tracking/BMOU2728050" style="width: 100%; height: 100%" frameborder="0" />
    <div v-if="tracking_route" class="row">
      <div class="col">
        <div class="row margin-bottom" v-for="point in tracking_route.points">
          <div class="col-1">
            <img src="https://i.imgur.com/9bTNDZI.png" style="max-width: 50px">
          </div>
          <div class="col">
            <p><strong>{{ point.name }}</strong></p>
            <table class="table table-sm">
              <tr>
                <th>Status</th>
                <th>Date Time</th>
                <th>Voyage No</th>
                <th>Type</th>
              </tr>
              <tr v-for="(event, index) in point.events">
                <td style="width: 50%">{{ event.description }}</td>
                <td style="width: 20%">{{ DateFromTimestamp(event.date) }}</td>
                <td style="width: 20%">{{ event.voyage }}</td>
                <td>{{ TransportType(event.type) }}</td>
              </tr>
            </table>

          </div>
        </div>
      </div>
      <!-- <div class="col">

  <GmapMap
    :center="last_mark"
    :zoom="7"
    map-type-id="terrain"
  >
    <GmapMarker
      :key="index"
      v-for="(m, index) in markers"
      :position="m.position"
      :clickable="true"
      :draggable="false"
      @click="ToggleInfoWindow(m,index)"
    />

    <GmapPolyline v-if="path.length > 0" :path="path" :editable="false" ref="polyline"></GmapPolyline>

  </GmapMap>

      </div> -->
    </div>
  </div>
</template>

<style scoped>
p {
  margin-bottom: 0;
}

.margin-bottom {
  margin-bottom: 20px;
}

.vue-map-container {
  width: 100%;
  height: 100%;
  max-height: 500px;
}

iframe {
  min-height: 400px;
}
</style>

<script>
export default {
  name: 'sea-rates',
  props: ['value'],
  data() {
    return {
      tracking_route: null,
      last_mark: { lat: 10, lng: 10 },
      path: [
        { lat: -23, lng: -45 },
        { lat: 10, lng: 10 },
      ],
      markers: []
    }
  },
  methods: {
    TransportType: function (type_id) {
      switch (type_id) {
        case 1:
          return "RAIL";
        case 2:
          return "AIR";
        case 3:
          return "SEA";
        case 4:
          return "TRUCK";
      }
    },
    GetTrackApi: function (value) {
      this.$data.tracking_route = value;
      this.$data.markers = this.$data.tracking_route.points.map ( value => {
        return {
          position: {
            lat: parseFloat(value.lat),
            lng: parseFloat(value.lng)
          }
        }
      })

      this.$data.last_mark = this.$data.markers[this.$data.markers.length - 1].position

      this.$data.path = this.$data.tracking_route.points.map ( value => {
        return {
          lat: parseFloat(value.lat),
          lng: parseFloat(value.lng)
        }
      })
    },
    DateFromTimestamp: function (date) {
      let data = new Date(date * 1000)
      let data_hours = data.getUTCHours()
      let data_minutes = data.getUTCMinutes()
      let data_seconds = data.getUTCSeconds()

      data_hours   = data_hours < 10   ? "0" + data_hours   : data_hours;
      data_minutes = data_minutes < 10 ? "0" + data_minutes : data_minutes;
      data_seconds = data_seconds < 10 ? "0" + data_seconds : data_seconds;

      return data.getUTCMonth() + "/" + data.getUTCDay() + "/" + data.getUTCFullYear() + " " + data_hours + ":" + data_minutes + ":" + data_seconds
    },
    ToggleInfoWindow: function(marker, idx) {
      this.infoWindowPos = marker.position;
      this.infoContent = marker.infoText;
      //check if its the same marker that was selected if yes toggle
      if (this.currentMidx == idx) {
        this.infoWinOpen = !this.infoWinOpen;
      }
      //if different marker set infowindow to open and reset current marker index
      else {
        this.infoWinOpen = true;
        this.currentMidx = idx;
      }
    }
  },
  mounted: function () {
    this.GetTrackApi(this.$props.value);

    var cssLink = document.createElement("link");
    cssLink.href = "style.css"; 
    cssLink.rel = "stylesheet"; 
    cssLink.type = "text/css"; 
    frames['iframe1'].document.head.appendChild(cssLink);
  },
  watch: {
    value: function (newValue, oldValue) {
      this.GetTrackApi(newValue);
    }
  }
}
</script>