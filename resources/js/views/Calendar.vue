<template>
    <div class="container-fluid">
        <div id="ui-view">
            <div class="panel panel-success">
                <div class="card">

                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                Calendar
                            </div>

                            <div class="col-auto">
                                <multiselect
                                    v-model="filter.loadingLocations"
                                    placeholder="Select a location"
                                    :options="loadingLocations"
                                    :show-labels="false"
                                    :allow-empty="true"
                                    :multiple="true"
                                    @select="handleFilter()"
                                    @remove="handleFilter()"
                                    >
                                </multiselect>
                            </div>

                            <div class="col-auto">
                                <multiselect
                                    v-model="filter.truckstatus"
                                    placeholder="Select a status"
                                    :options="truckStatus"
                                    :show-labels="false"
                                    :allow-empty="true"
                                    :multiple="true"
                                    @select="handleFilter()"
                                    @remove="handleFilter()"
                                    >
                                </multiselect>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="scrollme">
                            <div id="app">
                                <FullCalendar
                                    ref="aureaCalendar"
                                    :options="calendarOptions"
                                />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <dinamic-modal
            v-if="dinamicModal.isOpen"
            :modalId="dinamicModal.id"
            :modalTitle="dinamicModal.title"
            :contentComponent="dinamicModal.component"
            :componentProps="dinamicModal.props"
            :contentHtml="dinamicModal.html"
            :saveAction="dinamicModal.saveAction"
            @callback="handleDinamicModalCallback"
            @update-data="handleUpdateData"
        />
    </div>
</template>


<style scoped>
    .scrollme{
        min-height: calc(100vh - 450px) !important;
        overflow-x: auto;
    }

    #app {
		height: 67vh;
		margin-left: auto;
		margin-right: auto;
	}
</style>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import moment from 'moment'
import TruckModal from '../components/TruckModal';

import utilidade from "./../mixins/utilidade";

    export default {
        name: 'calendar',
        components: {
            FullCalendar,
            'truck-modal': TruckModal,
        },
        mixins:[utilidade],
        data: function() {
            return {
                filter:{
                    truckstatus:[],
                    loadingLocations: [],
                },
                truckStatus: ['not set','transit','unloading', 'completed'],
                loadingLocations: [],
                selectedTruckData: {},
                dinamicModal: {
                    isOpen: false,
                    id: null,
                    title: '',
                    component: null,
                    props: {},
                    html: '',
                    saveAction: null
                },

                defaultModalValues: {
                    status: {
                        id: 'status',
                        title: 'Edit truck status',
                        component: 'truck-modal',
                        saveAction: this.SaveTruckData,
                    },
                },

                eventGuid: 1,
                calendarOptions: {
                    plugins: [
                        dayGridPlugin,
                        timeGridPlugin,
                        interactionPlugin
                    ],
                    initialView: 'dayGridWeek',
                    headerToolbar: {
                        left: 'today prev,next',
                        center: 'title',
                        right: 'dayGridWeek,dayGridMonth,timeGridDay'
                    },
                    editable: true,
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: false,
                    weekends: true,
                    select: this.handleDateSelect,
                    eventClick: this.handleEventClick,
                    eventsSet: this.handleEvents,
                    events: [],
                    eventDrop: this.eventDrop
                },
            }
        },

        methods: {

            handleFilter() {
                this.getEvents();
            },

            handleEvents(events) {
                this.currentEvents = events
            },

            handleUpdateData({ type, value, truckId }) {
                if (!this.selectedTruckData) {
                    this.selectedTruckData = {};
                }

                this.$set(this.selectedTruckData, type, value);
                this.$set(this.selectedTruckData, 'id', truckId);

                const eventIndex = this.calendarOptions.events.findIndex(event => event.extendedProps.truck.id === truckId);

                if (eventIndex !== -1) {
                    let title = this.calendarOptions.events[eventIndex].title;
                    const newStatus = `Status: ${value}`;

                    this.calendarOptions.events = [...this.calendarOptions.events, {...this.calendarOptions.events[eventIndex], title: title.replace(/Status: .*/, newStatus)}];
                }
            },

            handleDinamicModalCallback(payload) {
                this.dinamicModal.isOpen = false;
                $('#dinamic-modal').modal('hide');
            },
            
            SaveTruckData(){
                const truck = this.selectedTruckData;

                this.$http.post(`/api/vehicle/${truck.id}/update`, this.selectedTruckData)
                .then(() => {
                    this.dinamicModal.isOpen = false;
                    $('#dinamic-modal').modal('hide');
                    this.$toaster.success('Date saved')
                })
                .catch(error => {
                    this.$toaster.error('Error saving date')
                })

            },

            openModal(modalType, typeValue, labelValue, truckId) {

                const eventToUpdate = this.calendarOptions.events.find(event => event.extendedProps.truck.id === Number(truckId));
                const modal = this.defaultModalValues[modalType];

                this.dinamicModal = {
                    isOpen: true,
                    id: modal.id,
                    title: modal.title +' '+ eventToUpdate.plate,
                    component: this.$options.components[modal.component],
                    props: {
                        data: eventToUpdate,
                        error: null,
                        modalIsOpen: this.dinamicModal.isOpen,
                        type: typeValue,
                        field: 'status',
                        label: labelValue
                    },
                    html: modal.html || '',
                    saveAction: modal.saveAction,

                };


                this.$nextTick(() => {
                    this.dinamicModal.isOpen = true;
                    $('#dinamic-modal').modal('show');
                });
            },

            handleEventClick(info) {
                const truckId = info.event.extendedProps.truck.id;
                this.openModal('status', 'status', 'Truck Status', truckId )
            },

            next() {
                console.log("next")
                this.$refs.calendar.$emit('next')
            },

            toggleWeekends: function() {
                console.log("toggleWeekends")
                this.calendarOptions.prev
            },

            getColorFromString(str) {
                let hash = 0;
                for (let i = 0; i < str.length; i++) {
                    hash = str.charCodeAt(i) + ((hash << 5) - hash);
                }
                const r = (hash >> 24) & 0xFF;
                const g = (hash >> 16) & 0xFF;
                const b = (hash >> 8) & 0xFF;
                return `rgba(${r}, ${g}, ${b}, 0.3)`;
            },

            eventDrop: function(info) {
                const eventId = info.event.id;
                const splitData = eventId.split('_');
                const orderId = splitData[0];
               
                const type = 'loadings.vehicles.truck_unloading_datetime';
                const newDate = moment(info.event.start).format('YYYY-MM-DD')

                this.$http.post('/api/order/'+orderId+'/date', { date: newDate, type: type })
                    .then(() => {
                        this.$toaster.success('Date saved')
                    })
                    .catch(error => {
                        this.$toaster.error('Error saving date')
                    })
            },

            getEvents: async function() {
                let eventId = 1;
                const params = {};
                const events = [];
                const locations = [];

                if (this.filter.truckstatus && this.filter.truckstatus.length) {
                    params.status = this.filter.truckstatus;
                }

                if (this.filter.loadingLocations && this.filter.loadingLocations.length) {
                    params.loading_location = this.filter.loadingLocations;
                }

                try {

                    const response = await this.$http.get('/api/orders/calendarEvents/', { params });

                    response.body.events.forEach(event => {
                        const driver = event.driver ? event.driver : '';
                        const truckStatus = event.status || 'not set';

                        locations.push(event.loading_location);

                        events.push({
                            id: event.order_id +'_'+ event.id +'_'+ eventId++,
                            title: `PO: ${event.order_number} \n Weight: ${event.net_gross} \n Driver: ${this.capitalizeWords(driver)} \n Plate: ${event.plate} \n Status: ${truckStatus} \n Loading Location: ${event.loading_location}`,
                            start: event.truck_unloading_datetime,
                            borderColor: "#fff",
                            backgroundColor: this.getColorFromString(event.order_number),
                            textColor: "#000",
                            display: "block",
                            extendedProps: {
                                truck: {id: event.id}
                            }
                        });

                    });

                    this.calendarOptions.events = events;
                    const locationsOptions = locations.filter((item, index) => locations.indexOf(item) === index);
                    this.loadingLocations = locationsOptions;
   
                } catch (error) {
                    console.error('Error fetching calendar events:', error);
                }
            }

        },

        mounted: function () {
            this.getEvents();
        },

    }
</script>