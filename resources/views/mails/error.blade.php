<table border=1 cellspacing=0 cellpadding=3>
    <tbody>
        <tr>
            <th>URL</th>
            <td><?= $url ?></td>
        </tr>
        <tr>
            <th>Method</th>
            <td><?= $method ?></td>
        </tr>
        <tr>
            <th>headers</th>
            <td><?= $headers ?></td>
        </tr>
        <tr>
            <th>user</th>
            <td><?= $user ?></td>
        </tr>
        <tr>
            <th>inputs</th>
            <td><?= $inputs ?></td>
        </tr>
        <tr>
            <th>message</th>
            <td><?= $error_message ?></td>
        </tr>
        <tr>
            <th>error_category</th>
            <td><?= $error_category ?></td>
        </tr>
        <tr>
            <th>trace</th>
            <td>
                @foreach ($trace as $value)
                @php print_r($value) @endphp
                @endforeach
            </td>
        </tr>
    </tbody>
</table>