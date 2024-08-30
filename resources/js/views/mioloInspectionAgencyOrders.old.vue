        <tr>
            <th>Importer name</th>
            <th>Order date</th>
            <th>Order status</th>
            <th>&nbsp;</th>
        </tr>
        <tr v-for="(order, index) in page_orders" v-if="order.document.length > 0">
            <td>{{ order.owner.name }}</td>
            <td>{{ FormatDate(order.created_at) }}</td>
            <td>{{ order.status.name }}</td>
            <td class="text-right">
                <router-link :to="{ name: 'Order information', params: { id: order.id } }" class="btn btn-primary btn-sm" v-if="order.status.id == 3 || order.status.information == true">Order information</router-link>
                <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" :href='"#modal-" + index'>File</button> -->
                <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" :href='"#modal-obs-" + index'>Observations</button> -->
                <div class="modal fade text-left" :id="'modal-' + index" v-if="order.inspection_agency">
                    <div class="modal-dialog modal-success">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">File</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">Inspection Start Date and time</label>
                                            <div class="row">
                                                <div class="col">
                                                    <input type="date" class="form-control" v-model="order.inspection_agency.inspection_start_date">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" v-model="order.inspection_agency.inspection_start_time">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">Inspection End Date and time</label>
                                            <div class="row">
                                                <div class="col">
                                                    <input type="date" class="form-control" v-model="order.inspection_agency.inspection_end_date">
                                                </div>
                                                <div class="col">
                                                    <input type="time" class="form-control" v-model="order.inspection_agency.inspection_end_time">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <label for="">Certificate of Weight</label>
                                <popcorn-upload-new name="certificate_weight" :allowed_pattern="/^application\/pdf$/" v-model="order.document[0].certificate_weight"></popcorn-upload-new>
                                <label for="">Certificate of Quality</label>
                                <popcorn-upload-new name="certificate_quality" :allowed_pattern="/^application\/pdf$/" v-model="order.document[0].certificate_quality"></popcorn-upload-new>
                                <label for="">Report</label>
                                <popcorn-upload-new name="report" :allowed_pattern="/^application\/pdf$/" v-model="order.document[0].report"></popcorn-upload-new>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" @click="Save(order)">Save</button>
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
                                <dr-history :url="'/api/inspectionObservation/' + order.inspection_agency.id" v-if="order.inspection_agency"></dr-history>
                                
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

            </td>
        </tr>
