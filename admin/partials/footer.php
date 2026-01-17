<?php
/* =========================================================
   admin/partials/footer.php — TERMINAL FOOTER
   ✅ JS Logic for Animation
   ✅ Closing Tags
   ========================================================= */
?>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    });
</script>

</body>
</html>