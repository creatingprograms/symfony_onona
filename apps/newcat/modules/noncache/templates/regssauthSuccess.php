<form action="/guard/login" method="post">
	<b>Данная почта уже зарегистрирована. Пожалуйста авторизируйтесь.</b>
	<table>
		<tfoot>
			<tr>
				<td colspan="2">
					<input type="submit" value="Авторизоваться" /> <a href="/guard/forgot_password">Восстановление пароля</a> &nbsp; <a href="/register">Хотите зарегистрироваться?</a></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<th>
					<label for="signin_username">Ваш E-mail</label></th>
				<td>
					<input id="signin_username" name="signin[username]" type="text" value="<?=$email?>" /></td>
			</tr>
			<tr>
				<th>
					<label for="signin_password">Пароль</label></th>
				<td>
					<input id="signin_password" name="signin[password]" type="password" /></td>
			</tr>
			<tr>
				<th>
					<label for="signin_remember">Запомнить</label></th>
				<td>
					<input id="signin_remember" name="signin[remember]" type="checkbox" /></td>
			</tr>
		</tbody>
	</table>
</form>
