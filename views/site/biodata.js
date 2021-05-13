
$(document).ready(function () {
    // event setelah tambah row
    $(".dynamicform_wrapper").on("afterInsert", function (e, item) {

        // update - agar select2 tidak terselect
        // $(item).find("select[name*='[id_barang]']").val(null).trigger('change')
        // $(item).find("select[name*='[id_kemasan]']").val(null).trigger('change')
        // $(item).find("select[name*='[id_satuan]']").val(null).trigger('change')

        // $(item).find("select[name*='[id_barang]']").on('select2:select', function (e) {
        //     let index = $(this).closest("tr").index()
        //     let barangDipilih = e.params.data

        //     $(`#hibahprogramdetail-${index}-stok_gudang-disp`).val(barangDipilih.stok_gudang).trigger("change")
        //     $(`#hibahprogramdetail-${index}-id_satuan`).val(barangDipilih.id_satuan).trigger("change")
        //     $(`#hibahprogramdetail-${index}-harga_satuan-disp`).val(barangDipilih.harga_beli_terakhir).trigger("change")

        //     $(`#hibahprogramdetail-${index}-expired_date`).focus()
        // })

        $(".dynamicform_wrapper .form-options-item").each(function (index) {
            $(this).find('.nomor').html((index + 1))
        })

        // $(item).find("select[name*='[id_barang]']").select2('open')

    })

    $(".dynamicform_wrapper").on("afterDelete", function (e) {
        $(".dynamicform_wrapper .form-options-item").each(function (index) {
            $(this).find('.nomor').html((index + 1))
        })
    })

})