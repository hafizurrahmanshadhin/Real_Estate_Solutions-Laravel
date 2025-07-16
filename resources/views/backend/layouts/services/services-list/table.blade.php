<div class="col-lg-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Service List</h5>
            <button type="button" class="btn btn-primary btn-sm" id="addNewService">Add New</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-services"
                    class="table table-bordered table-striped align-middle dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="column-id">#</th>
                            <th class="column-content">Package & Footage Range</th>
                            <th class="column-content">Service Items</th>
                            <th class="column-content">Items Count</th>
                            <th class="column-content">Total Quantity</th>
                            <th class="column-content">Total Price</th>
                            <th class="column-status">Status</th>
                            <th class="column-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Dynamic Data --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
