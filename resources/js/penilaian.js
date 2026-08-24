export default () => ({
    activeTab: 'ki3',
    isDirty: false,

    init() {
        window.addEventListener('beforeunload', (e) => {
            if (this.isDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    },

    confirmLeave(e) {
        if (this.isDirty && !confirm('Ada nilai yang belum disimpan! Perubahan akan hilang jika berpindah halaman. Lanjutkan?')) {
            e.preventDefault();
        }
    },

    handleSubmit(e) {
        const inputs = Array.from(e.target.querySelectorAll('.input-score'));
        const emptyInputs = inputs.filter(i => i.value.trim() === '');
        
        if (emptyInputs.length > 0 && !confirm(`Terdapat ${emptyInputs.length} kolom nilai yang belum diisi.\n\nYakin ingin menyimpan data yang sudah ada?`)) {
            e.preventDefault();
            return;
        }
        this.isDirty = false;
    },

    navigateTable(e) {
        if (!['Enter', 'ArrowDown', 'ArrowUp'].includes(e.key)) return;
        const input = e.target;
        if (!input.classList.contains('input-score') && !input.classList.contains('input-catatan')) return;
        
        const td = input.closest('td');
        const tr = input.closest('tr');
        if (!td || !tr) return;

        const colIndex = Array.from(tr.children).indexOf(td);

        if (e.key === 'Enter' || e.key === 'ArrowDown') {
            e.preventDefault();
            const nextRow = tr.nextElementSibling;
            if (nextRow) nextRow.children[colIndex]?.querySelector('input')?.focus();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const prevRow = tr.previousElementSibling;
            if (prevRow) prevRow.children[colIndex]?.querySelector('input')?.focus();
        }
    }
});

export function rowKI3(initialData) {
    return {
        harian: initialData.harian ?? '',
        tugas: initialData.tugas ?? '',
        quiz: initialData.quiz ?? '',
        uts: initialData.uts ?? '',
        uas: initialData.uas ?? '',
        isRowDirty: false,

        get isValid() {
            return [this.harian, this.tugas, this.quiz, this.uts, this.uas]
                .every(v => v !== '' && v !== null && !isNaN(v));
        },

        get scoreAkhir() {
            if (!this.isValid) return null;
            const score = (Number(this.harian) * 0.20) + 
                          (Number(this.tugas) * 0.20) + 
                          (Number(this.quiz) * 0.10) + 
                          (Number(this.uts) * 0.25) + 
                          (Number(this.uas) * 0.25);
            return score.toFixed(2);
        },

        get predikat() {
            if (this.scoreAkhir === null) return '-';
            const score = Number(this.scoreAkhir);
            if (score >= 90) return 'A';
            if (score >= 80) return 'B';
            if (score >= 75) return 'C';
            return 'D';
        },

        get predikatBadge() {
            const badgeMap = {
                'A': 'bg-emerald-100 text-emerald-800',
                'B': 'bg-sky-100 text-sky-800',
                'C': 'bg-amber-100 text-amber-800',
                'D': 'bg-rose-100 text-rose-800',
                '-': 'bg-slate-100 text-slate-600'
            };
            return badgeMap[this.predikat] || badgeMap['-'];
        },

        get statusLabel() {
            if (this.scoreAkhir === null) return '-';
            return Number(this.scoreAkhir) >= 75 ? 'Tuntas' : 'Remedial';
        },

        get statusBadge() {
            if (this.scoreAkhir === null) return 'bg-slate-100 text-slate-600';
            return Number(this.scoreAkhir) >= 75 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800';
        },

        validateInput(field) {
            if (this[field] > 100) this[field] = 100;
            if (this[field] < 0) this[field] = 0;
            this.isRowDirty = true;
        }
    };
}

export function rowKI4(initialData) {
    return {
        praktik: initialData.praktik ?? '',
        proyek: initialData.proyek ?? '',
        portofolio: initialData.portofolio ?? '',
        isRowDirty: false,

        get isValid() {
            return [this.praktik, this.proyek, this.portofolio]
                .every(v => v !== '' && v !== null && !isNaN(v));
        },

        get scoreAkhir() {
            if (!this.isValid) return null;
            const score = (Number(this.praktik) * 0.40) + 
                          (Number(this.proyek) * 0.30) + 
                          (Number(this.portofolio) * 0.30);
            return score.toFixed(2);
        },

        get predikat() {
            if (this.scoreAkhir === null) return '-';
            const score = Number(this.scoreAkhir);
            if (score >= 90) return 'A';
            if (score >= 80) return 'B';
            if (score >= 75) return 'C';
            return 'D';
        },

        get predikatBadge() {
            const badgeMap = {
                'A': 'bg-emerald-100 text-emerald-800',
                'B': 'bg-sky-100 text-sky-800',
                'C': 'bg-amber-100 text-amber-800',
                'D': 'bg-rose-100 text-rose-800',
                '-': 'bg-slate-100 text-slate-600'
            };
            return badgeMap[this.predikat] || badgeMap['-'];
        },

        get statusLabel() {
            if (this.scoreAkhir === null) return '-';
            return Number(this.scoreAkhir) >= 75 ? 'Tuntas' : 'Remedial';
        },

        get statusBadge() {
            if (this.scoreAkhir === null) return 'bg-slate-100 text-slate-600';
            return Number(this.scoreAkhir) >= 75 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800';
        },

        validateInput(field) {
            if (this[field] > 100) this[field] = 100;
            if (this[field] < 0) this[field] = 0;
            this.isRowDirty = true;
        }
    };
}