<div class="form-group">
    <label for="formGroupExampleInput">Pilih Project</label>
    <select class="form-control" id="project_nrc" name="project_nrc" onchange="loadnrc()">
        <option selected>Pilih Project</option>
        <?php foreach ($query as $key) : ?>
            <option value="<?= $key->nama_gl; ?>"><?= $key->ID_project; ?> / <?= $key->nama; ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div id="ln"></div>