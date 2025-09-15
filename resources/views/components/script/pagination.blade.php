    {{-- jQuery Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Search and Pagination JavaScript --}}
    <script>
        $(document).ready(function() {
            const $rows = $("#Table tbody tr");
            let limit = parseInt($("#row-limit").val());
            let currentPage = 1;
            let totalPages = Math.ceil($rows.length / limit);

            function updateTable() {
                $rows.hide();

                let start = (currentPage - 1) * limit;
                let end = start + limit;

                $rows.slice(start, end).show();

                // Update info halaman
                $("#page-info").text(`Page ${currentPage} of ${totalPages}`);

                // Disable prev/next sesuai kondisi
                $("#prev-page").prop("disabled", currentPage === 1);
                $("#next-page").prop("disabled", currentPage === totalPages);
            }

            // Apply awal
            updateTable();

            // Kalau ganti jumlah data
            $("#row-limit").on("change", function() {
                limit = parseInt($(this).val());
                currentPage = 1;
                totalPages = Math.ceil($rows.length / limit);
                updateTable();
            });

            // Tombol prev
            $("#prev-page").on("click", function() {
                if (currentPage > 1) {
                    currentPage--;
                    updateTable();
                }
            });

            // Tombol next
            $("#next-page").on("click", function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    updateTable();
                }
            });

            // Filter Data By Search
            $("#btn-search").on("keyup", function() {
                let value = $(this).val().toLowerCase();
                $("#Table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });

                // Update pagination after search
                const $visibleRows = $("#Table tbody tr:visible");
                totalPages = Math.ceil($visibleRows.length / limit);
                currentPage = 1;

                if (value === '') {
                    // If search is cleared, show all rows with pagination
                    updateTable();
                } else {
                    // If searching, hide pagination info
                    $("#page-info").text(`Showing ${$visibleRows.length} results`);
                    $("#prev-page").prop("disabled", true);
                    $("#next-page").prop("disabled", true);
                }
            });
        });
    </script>
