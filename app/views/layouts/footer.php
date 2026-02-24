</div> <!-- Close main container -->

<footer class="mt-5 pt-5 pb-4 text-white" style="background: linear-gradient(90deg, #1e3a8a, #1e40af);">
    <div class="container">

        <div class="row g-4">

            <div class="col-md-3">
                <h5 class="fw-bold">Blueprint</h5>
                <p class="small text-white-50">
                    Clinical task management system for psychology wards.
                    Streamlining patient care and session tracking.
                </p>
            </div>

            <div class="col-md-3">
                <h6 class="fw-bold">Quick Links</h6>
                <ul class="list-unstyled small">
                    <li><a href="<?= url('dashboard') ?>" class="text-decoration-none text-white-50 hover-white">Dashboard</a></li>
                    <li><a href="<?= url('wards/hope') ?>" class="text-decoration-none text-white-50 hover-white">Hope Ward</a></li>
                    <li><a href="<?= url('wards/manor') ?>" class="text-decoration-none text-white-50 hover-white">Manor Ward</a></li>
                    <li><a href="<?= url('wards/lakeside') ?>" class="text-decoration-none text-white-50 hover-white">Lakeside Ward</a></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h6 class="fw-bold">Support</h6>
                <ul class="list-unstyled small">
                    <li><a href="#" class="text-decoration-none text-white-50 hover-white">Help Center</a></li>
                    <li><a href="#" class="text-decoration-none text-white-50 hover-white">Documentation</a></li>
                    <li><a href="#" class="text-decoration-none text-white-50 hover-white">Contact</a></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h6 class="fw-bold">Clinical Info</h6>
                <ul class="list-unstyled small text-white-50">
                    <li>Hope: 12 beds</li>
                    <li>Manor: 10 beds</li>
                    <li>Lakeside: 10 beds</li>
                    <li>CORE-10 Tracking</li>
                </ul>
            </div>

        </div>

        <hr class="border-white opacity-25 my-4">

        <div class="d-flex justify-content-between flex-wrap small text-white-50">
            <div>&copy; <?= date('Y') ?> Blueprint. All rights reserved.</div>
            <div>
                <span class="badge bg-white bg-opacity-25 me-2">NHS Compliant</span>
                <span class="badge bg-white bg-opacity-25 me-2">GDPR Ready</span>
                <span class="badge bg-white bg-opacity-25">Clinical Safety</span>
            </div>
        </div>

    </div>
</footer>

<script src="<?= asset('js/script.js'); ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>