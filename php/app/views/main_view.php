<link rel="stylesheet" href="/style/main.css">
<script src="/script/main.js"></script>

<svg style="display: none;">
	<defs>
		<symbol id="icon-arrow" viewBox="0 0 0.9 0.9" width="20" height="20">
			<path clip-rule="evenodd" d="M.123.273a.037.037 0 0 1 .053 0L.45.547.723.274a.037.037 0 1 1 .053.053l-.3.3a.037.037 0 0 1-.053 0l-.3-.3a.037.037 0 0 1 0-.053"/>
		</symbol>
	</defs>
</svg>
<div id="wrapper">
	<div id="content">
		<header>
			<h1>Users comments <span>board</span>!</h1>
		</header>
		<div id="filters-block">
			<p>Comments filter:</p>
			<div id="filters">
				<div class="item-filter" filter="name">
					<span>Name</span>
					<svg class="desc" width="20" height="20"><use xlink:href="#icon-arrow"></use></svg>
				</div>
				<div class="item-filter" filter="email">
					<span>E-Mail</span>
					<svg class="desc" width="20" height="20"><use xlink:href="#icon-arrow"></use></svg>
				</div>
				<div class="item-filter" filter="date">
					<span>Date</span>
					<svg class="desc" width="20" height="20"><use xlink:href="#icon-arrow"></use></svg>
				</div>
			</div>
		</div>
		<div id="comments">

		</div>
		<form id="add-new-comment">
			<input type="button" id="show-form" value="добавить новую запись"/>
			<div id="form-name-email">
				<input type="text" name="name" placeholder="name" pattern="^[a-zA-Z0-9]{4,}$" required/>
				<input type="text" name="email" placeholder="example@mail.com" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required/>
			</div>
			<textarea name="comment" required></textarea>
			<div id="captcha-block">
				<div id="captcha-img">
				</div>
				<input type="text" name="captcha" placeholder="captcha" required/>
			</div>
			<input type="hidden" name="ip" value="<?=$_SERVER['REMOTE_ADDR'];?>"/>
			<input type="hidden" name="browser" value="<?=$_SERVER['HTTP_USER_AGENT'];?>"/>
			<div id="form-buttons">
				<input type="submit" value="submit"/>
				<input type="button" id="clear-form" value="clear"/>
			</div>
			<p class="request-info" id="request-captcha"></p>
			<p class="request-info" id="request-db"></p>
			<p class="request-info" id="request-validator"></p>
			<p class="request-info" id="request-validator"></p>
		</form>
		<footer>
		</footer>
	</div>
</div>
