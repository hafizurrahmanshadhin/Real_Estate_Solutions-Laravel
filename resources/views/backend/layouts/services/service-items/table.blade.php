<div class="col-lg-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Services Item List</h5>
            <button type="button" class="btn btn-primary btn-sm" id="addNewItem">Add New</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-service-items"
                    class="table table-bordered table-striped align-middle dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="column-id">#</th>
                            <th class="column-content">Item Name</th>
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
