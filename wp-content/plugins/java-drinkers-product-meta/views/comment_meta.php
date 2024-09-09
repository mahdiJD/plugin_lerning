<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <label for="jdpm_score">امتیاز دیدگاه:</label>
            </th>
            <td>
                <input type="number" step="1" min="0" max="10" id="jdpm_score" name="jdpm_score" value="<?php echo esc_attr($score); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="jdpm_special">  دیدگاه ویژه:</label>
            </th>
            <td>
                <input type="checkbox" id="jdpm_special" name="jdpm_special" <?php echo checked($special) ?> >
            </td>
        </tr>
    </tbody>
</table>