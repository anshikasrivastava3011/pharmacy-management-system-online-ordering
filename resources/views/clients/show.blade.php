<div class="modal fade" id="show-client" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered modal-xl">

        <div class="modal-content border-0 shadow-lg rounded-4 bg-dark text-white">

            <div class="modal-header border-secondary">

                <h4 class="modal-title fw-bold">
                    Client Details
                </h4>

                <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body p-4">

                <div class="text-center mb-4">

                    <img id="image"
                        src=""
                        class="rounded-circle shadow"
                        width="130"
                        height="130"
                        style="object-fit: cover;">

                </div>

                <div class="row g-3 mb-4">

                    <div class="col-md-6">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Name</small>
                            <h5 id="name" class="mb-0 mt-1"></h5>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Email</small>
                            <h6 id="email" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">National ID</small>
                            <h6 id="national-id" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Gender</small>
                            <h6 id="gender" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Phone</small>
                            <h6 id="phone" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Date of Birth</small>
                            <h6 id="date-of-birth" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-dark table-hover table-bordered rounded-3 overflow-hidden text-center align-middle">

                        <thead class="table-secondary text-dark">

                            <tr>
                                <th>Postal Code</th>
                                <th>Area</th>
                                <th>Street</th>
                                <th>Building</th>
                                <th>Floor</th>
                                <th>Flat</th>
                                <th>Main</th>
                            </tr>

                        </thead>

                        <tbody id="client-body-addresses"></tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

function clientshowmodalShow(event) {

    var itemId = event.target.id;

    $('#client-body-addresses').empty();

    $.ajax({

        url: "{{ route('clients.show', ':id') }}".replace(':id', itemId),
        method: "GET",

        success: function(response) {

            var imagePath =
                "{{ asset('storage/clients_Images/:image_name') }}"
                .replace(':image_name', response.client.avatar_image);

            $('#image').attr('src', imagePath);

            $('#name').text(response.user.name);
            $('#email').text(response.user.email);
            $('#national-id').text(response.client.id);
            $('#gender').text(response.client.gender);
            $('#phone').text(response.client.phone);
            $('#date-of-birth').text(response.client.date_of_birth);

            var table_body = $('#client-body-addresses');

            for (var address of response.addresses) {

                var mainStreet = address.is_main
                    ? '<span class="badge bg-success">Yes</span>'
                    : '<span class="badge bg-danger">No</span>';

                var record = `
                    <tr>
                        <td>${address.area_id}</td>
                        <td>${address.area_name}</td>
                        <td>${address.street_name}</td>
                        <td>${address.building_number}</td>
                        <td>${address.floor_number}</td>
                        <td>${address.flat_number}</td>
                        <td>${mainStreet}</td>
                    </tr>
                `;

                table_body.append(record);
            }
        }
    });
}

</script>