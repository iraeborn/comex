        <tr>
            <th>Importer name</th>
            <th>Order date</th>
            <!-- <th>Order status</th> -->
            <th>&nbsp;</th>
        </tr>
        <tr v-for="(order, index) in page_orders" v-if="order.document.length > 0">
            <td>{{ order.owner.name }}</td>
            <td>{{ FormatDate(order.created_at) }}</td>
            <!-- <td>{{ order.status.name }}</td> -->
            <td class="text-right">
                <router-link :to="{ name: 'Order information', params: { id: order.id } }" class="btn btn-primary btn-sm" v-if="order.status.id == 3 || order.status.information == true">Order information</router-link>
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" :href='"#modal-" + index'>File</button>
                <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" :href='"#modal-obs-" + index'>Observations</button> -->

                <div class="modal fade text-left" :id="'modal-' + index">
                    <div class="modal-dialog modal-success">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">File</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-sm table-amarelo-spec">
                                    <tr>
                                        <th><label for="">MAPA Inspection Date</label></th>
                                        <th><label for="">DU-E Code</label></th>
                                        <th><label for="">RUC Code</label></th>
                                        <th><label for="">Access Key</label></th>
                                        <th><label for="">LPCO Key</label></th>
                                    </tr>
                                    <tr>
                                        <td><p>{{ FormatDate(order.document[0].inspection_date) }}</p></td>
                                        <td><p>{{ order.document[0].due_code }}</p></td>
                                        <td><p>{{ order.document[0].ruc_code }}</p></td>
                                        <td><p>{{ order.document[0].access_key }}</p></td>
                                        <td><p>{{ order.document[0].lpco_key }}</p></td>
                                    </tr>

                                </table>
                                <label for="">Requerimento M.A.P.A.</label><br>
                                <a v-if="order.document[0].mapa_document" :href="order.document[0].mapa_document" class="btn btn-primary" download>Download</a>
                                <p class="alert alert-danger" v-if="!order.document[0].mapa_document">File not yet uploaded</p>

                                <p class="title"><label for="">Requerimento M.A.P.A. signed</label></p>
                                <popcorn-upload-new name="mapa_document_signed" :allowed_pattern="/^application\/pdf$/" v-model="order.document[0].mapa_document_signed"></popcorn-upload-new>

                                <p><label for="">Requerimento Phyto</label></p>
                                <a v-if="order.document[0].phyto_document" :href="order.document[0].phyto_document" class="btn btn-primary" download>Download</a>
                                <p class="alert alert-danger" v-if="!order.document[0].phyto_document">File not yet uploaded</p>

                                <p class="title"><label for="">Requerimento Phyto signed</label></p>
                                <popcorn-upload-new name="phyto_document_signed" :allowed_pattern="/^application\/pdf$/" v-model="order.document[0].phyto_document_signed"></popcorn-upload-new>
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
                                <dr-history :url="'/api/mapaObservation/' + order.document[0].id"></dr-history>
                                
                                
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>

            </td>
        </tr>
