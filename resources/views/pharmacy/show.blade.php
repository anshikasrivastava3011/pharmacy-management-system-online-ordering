<!-- Pharmacy Show Modal -->
<div class="modal fade" id="showPharmacyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 bg-dark text-white">

            <div class="modal-header border-secondary">
                <h4 class="modal-title fw-bold">Pharmacy Details</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <div class="text-center mb-4">
                    <img id="pharmacyAvatar"
                        src=""
                        class="rounded-circle shadow"
                        width="120"
                        height="120"
                        style="object-fit: cover;">
                </div>

                <div class="row g-3">

                    <div class="col-12">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Pharmacy Name</small>
                            <h5 id="pharmacyName" class="mb-0 mt-1"></h5>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Owner ID</small>
                            <h6 id="pharmacyID" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Priority</small>
                            <h6 id="pharmacyPriority" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Owner Name</small>
                            <h6 id="pharmacyOwnerName" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Owner Email</small>
                            <h6 id="pharmacyOwnerEmail" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="bg-secondary bg-opacity-25 p-3 rounded-3">
                            <small class="text-info">Area</small>
                            <h6 id="pharmacyArea" class="mb-0 mt-1"></h6>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
function showPharmacyModal(event) {

    var pharmacyId = event.target.id;

    $.ajax({
        url: "{{ route('pharmacies.show', ':id') }}".replace(':id', pharmacyId),
        method: "GET",

        success: function(response) {

            $('#pharmacyAvatar').attr(
                "src",
                "{{ asset('storage/pharmacies_Images/image') }}"
                .replace('image', response.pharmacy.avatar_image)
            );

            $('#pharmacyID').text(response.pharmacy.id);
            $('#pharmacyName').text(response.pharmacy.pharmacy_name);
            $('#pharmacyOwnerName').text(response.user.name);
            $('#pharmacyOwnerEmail').text(response.user.email);
            $('#pharmacyPriority').text(response.pharmacy.priority);

            $('#pharmacyArea').text(
                response.areas.find(area =>
                    area.id === response.pharmacy.area_id
                ).name
            );
        }
    });
}
</script>