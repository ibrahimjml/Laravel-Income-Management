<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter By Date</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="filterForm" method="GET" action="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="month">Month:</label>
                                    <select id="month" name="month" class="form-control border">
                                        <option value="" disabled selected>Select month</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="year">Year:</label>
                                    <select id="year" name="year" class="form-control border">
                                        <option value="" disabled selected>Select year</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="dateFrom">Date From:</label>
                                    <input type="date" id="dateFrom" name="dateFrom" class="form-control border">
                                </div>
                                <div class="form-group">
                                    <label for="dateTo">Date To:</label>
                                    <input type="date" id="dateTo" name="dateTo" class="form-control border">
                                </div>
                            </div>

                        </form>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-warning" id="clearBtn">Clear</button>
                            <button type="submit" class="btn btn-primary" id="filterBtn">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>