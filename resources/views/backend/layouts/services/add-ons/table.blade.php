{{-- Add-ons List Table Start --}}
<div class="col-lg-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">All Add-ons List</h5>
            <button type="button" class="btn btn-primary btn-sm" id="addNewAddOn">Add New</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatable-add-ons"
                    class="table table-bordered table-striped align-middle dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="column-id">#</th>
                            <th class="column-content">Footage Size</th>
                            <th class="column-content">Service Item</th>
                            <th class="column-content">Quantity</th>
                            <th class="column-content">Price</th>
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
{{-- Add-ons List Table End --}}
