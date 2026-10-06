<script>
    document.getElementById('btn-add-variant')?.addEventListener('click', function() {
        const container = document.getElementById('variants-container');
        const template = document.getElementById('variant-template').innerHTML;
        const newIndex = Date.now();

        const newHtml = template.replace(/__IDX__/g, newIndex);
        container.insertAdjacentHTML('beforeend', newHtml);

        updateVariantNumbers();
    });

    function updateVariantNumbers() {
        const numbers = document.querySelectorAll('#variants-container .variant-number');
        numbers.forEach((el, i) => {
            el.innerText = i + 1;
        });
    }
</script>