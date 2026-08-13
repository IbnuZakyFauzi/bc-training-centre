const { JSDOM } = require('jsdom');

const html = `
<!DOCTYPE html>
<html>
<body>
<form id="test-form" x-data="{
    categoryId: '1',
    categoryMap: {'1':'EXC','2':'DZ'},
    equipmentMap: {},
    company: 'Test Co',
    stickerExpiredAt: '',
    hmStart: '',
    hmEnd: '',
    existingSopPayload: [],
    get selectedCategoryCode() {
        return this.categoryMap[this.categoryId] || '';
    },
    get totalHm() {
        let calc = parseFloat(this.hmEnd) - parseFloat(this.hmStart);
        return isNaN(calc) || calc < 0 ? '0.0' : calc.toFixed(1);
    },
    get hmError() {
        const start = parseFloat(this.hmStart);
        const end = parseFloat(this.hmEnd);
        if (this.hmStart === '' || this.hmEnd === '' || isNaN(start) || isNaN(end)) {
            return '';
        }
        if (end < start) {
            return 'HM Akhir tidak boleh lebih kecil dari HM Awal.';
        }
        return '';
    },
    fillExistingChecklist() {
        console.log('checklist filled');
    }
}">
    <div x-text=\"'totalHm=' + totalHm + ' hmError=' + hmError\"></div>
    <input x-model=\"hmStart\">
    <input x-model=\"hmEnd\">
    <div x-show=\"!hmError\">No error</div>
    <div x-show=\"hmError\" x-text=\"hmError\"></div>
</form>
</body>
</html>
`;

const dom = new JSDOM(html, { runScripts: 'dangerously', resources: 'usable' });
const window = dom.window;

// Load Alpine from CDN
const script = window.document.createElement('script');
script.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js';
script.defer = true;
window.document.body.appendChild(script);

setTimeout(() => {
    const form = window.document.getElementById('test-form');
    const div = form.querySelector('[x-text]');
    console.log('Div text:', div ? div.textContent : 'not found');
    console.log('Alpine errors:', window.console.log.mockInstances || 'no mock');
    
    // Check if Alpine component exists
    try {
        const alpineData = form.__x;
        console.log('Alpine data:', alpineData ? Object.keys(alpineData) : 'none');
    } catch (e) {
        console.log('Alpine check error:', e.message);
    }
    
    dom.window.close();
}, 2000);
