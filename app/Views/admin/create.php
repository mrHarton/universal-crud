<h1>Create New Collection</h1>
<form method="post" action="/admin/store">
    <label>Display Name:</label><br>
    <input name="display_name"><br><br>

    <label>Table Name (no spaces):</label><br>
    <input name="table_name"><br><br>

    <div id="fields">
        <h3>Fields</h3>
        <div class="field-group">
            <input name="fields[0][name]" placeholder="Field Name">
            <select name="fields[0][type]">
                <option value="string">String</option>
                <option value="text">Text</option>
                <option value="int">Integer</option>
                <option value="date">Date</option>
            </select>
        </div>
    </div>

    <button type="button" onclick="addField()">+ Add Field</button><br><br>
    <button type="submit">Create Collection</button>
</form>

<script>
let fieldIndex = 1;
function addField() {
    const fields = document.getElementById('fields');
    const group = document.createElement('div');
    group.className = 'field-group';
    group.innerHTML = `
        <input name="fields[${fieldIndex}][name]" placeholder="Field Name">
        <select name="fields[${fieldIndex}][type]">
            <option value="string">String</option>
            <option value="text">Text</option>
            <option value="int">Integer</option>
            <option value="date">Date</option>
        </select>
    `;
    fields.appendChild(group);
    fieldIndex++;
}
</script>
