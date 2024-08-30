        <tr>
            <th>Importer name</th>
            <th>Order date</th>
            <th>Order status</th>
            <th>&nbsp;</th>
        </tr>
        <tr v-for="(order, index) in page_orders" v-if="order.document.length > 0">
            <td>{{ order.owner.name }}</td>
            <td>{{ FormatDate(order.created_at) }}</td>
            <td>{{ GetStatus(order.document[0].certificate_fumigation_status) }}</td>
            <td class="text-right">
                <router-link :to="{ name: 'Order information', params: { id: order.id } }" class="btn btn-primary btn-sm" v-if="order.status.id == 3 || order.status.information == true">Order information</router-link>
                <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" :href='"#modal-" + index'>File</button> -->
                <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" :href='"#modal-obs-" + index'>Observations</button> -->

                <div class="modal fade text-left" :id="'modal-' + index">
                    <div class="modal-dialog modal-success">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">File</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <strong>Date of fumigation certificate</strong>
                                        <p>{{FormatDate(order.fumigation.date_of_fumigation_certificate)}}</p>
                                    </div>
                                    <div class="col">
                                        <strong>Date of conclusion</strong>
                                        <p>{{FormatDate(order.fumigation.date_of_conclusion)}}</p>
                                    </div>
                                </div>
                                <popcorn-upload-new :allowed_pattern="/^application\/pdf$/" v-model="order.document[0].certificate_fumigation"></popcorn-upload-new>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal" @click="Save(order)">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade text-left" :id="'modal-obs-' + index">
                    <div class="modal-dialog modal-success">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">File</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <dr-history :url="'/api/fumigationObservation/' + order.fumigation.id"></dr-history>
                                
                                
                                
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

            </td>
        </tr>