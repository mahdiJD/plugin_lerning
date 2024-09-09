<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <label for="jdpm_price">قیمت محصول</label>
            </th>
            <td>
                <input type="number" step="10000" id="jdpm_price" name="jdpm_price" value="<?php echo absint($price); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="jdpm_sale_price">قیمت فروش محصول</label>
            </th>
            <td>
                <input type="number" step="10000" id="jdpm_sale_price" name="jdpm_sale_price" value="<?php echo $sale_price; ?>">
            </td>
        </tr>
    </tbody>
</table>