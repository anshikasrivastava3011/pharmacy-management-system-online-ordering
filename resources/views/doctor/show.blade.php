<div class="modal fade" id="show-doctor" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 bg-dark text-white">

            <div class="modal-header border-secondary">
                <h4 class="modal-title fw-bold">Doctor Details</h4>

                <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body p-4">

                <div class="text-center mb-4">
                    <img id="doctorAvatar"
                        src=""
                        class="rounded-circle shadow"
                        width="120"
                        height="120"
                        style="object-fit: cover;">
                </div>

                <div class="row g-3">

                    <div class="col-12">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Doctor Name</small>
                            <h5 id="doctorName" class="mb-0 mt-1"></h5>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Email</small>
                            <h6 id="doctorEmail" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">National ID</small>
                            <h6 id="national-id" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Status</small>
                            <h6 id="doctorStatus" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Assigned Pharmacy</small>
                            <h6 id="pharmacy" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>

function doctorshowmodalShow(event) {

    var itemId = event.target.id;

    $.ajax({

        url: "{{ route('doctors.show', ':id') }}".replace(':id', itemId),
        method: "GET",

        success: function(response) {

            $('#doctorName').text(
                response.users.find(user =>
                    user.id === response.doctor.user_id
                ).name
            );

            $('#doctorEmail').text(
                response.users.find(user =>
                    user.id === response.doctor.user_id
                ).email
            );

            $('#national-id').text(response.doctor.id);

            $('#doctorStatus').text(
                response.doctor.is_banned ? 'Banned' : 'Active'
            );

            var pharmacyName = response.pharmacies.find(
                pharmacy => pharmacy.id === response.doctor.pharmacy_id
            ).pharmacy_name;

            $('#pharmacy').text(pharmacyName);

            var imagePath =
                "{{ asset('storage/doctors_Images/:image_name') }}"
                .replace(':image_name', response.doctor.avatar_image);

            $('#doctorAvatar').attr('src', imagePath);
        }
    });
}

</script>