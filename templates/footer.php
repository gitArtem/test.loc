</td>

<td width="300px" class="sidebar">
    <div class="sidebarHeader">Меню</div>
    <ul>
        <li><a href="/">Главная страница</a></li>
        <?php if (!empty($user)): ?>
            <li><a href="/articles/add">Добавить статью</a></li>
        <?php endif; ?>
        <?php if ($isEditable): ?>
            <li><a href="/articles/<?= $article->getId() ?>/edit">Редактировать</a></li>
            <li><a href="/articles/<?= $article->getId() ?>/delete">Удалить</a></li>
        <?php endif; ?>
    </ul>
</td>
</tr>
<tr>
    <td class="footer" colspan="2">Все права защищены (c)</td>
</tr>
</table>

</body>
</html>