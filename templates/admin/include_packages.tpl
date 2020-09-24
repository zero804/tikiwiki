{* $Id$ *}

{if ! $composer_available}
	{remarksbox type="warning" title="{tr}Composer not found{/tr}"}
		{tr}Composer could not be executed, so the automated check on the packages cannot be performed.{/tr}
		<br/>
		{tr}In <a href="javascript:void(0)" class="install-composer">Diagnose</a> tab you can install composer.{/tr}
	{/remarksbox}
{/if}

{if isset($composer_output)}
    {remarksbox type="note" title="{tr}Note{/tr}"}

    {tr}Result of executing the changes to the packages:{/tr}<br />
        <pre>{$composer_output}</pre>
    {/remarksbox}
{/if}

{if isset($composer_installed_errors)}
	{remarksbox type="warning" title="{tr}Composer errors{/tr}"}

	{tr}Composer returned some errors:{/tr}<br />
        <pre>{$composer_installed_errors}</pre>
	{/remarksbox}
{/if}

{if not empty($composer_environment_warning)}
	{remarksbox type="warning" title="{tr}Issues with composer environment{/tr}"}

    <p>{tr}Issues with composer environment:{/tr}</p>
    <ul>
    {foreach item=entry from=$composer_environment_warning}
        <li>{$entry}</li>
    {/foreach}
    </ul>
	{/remarksbox}
{/if}

{tabset name='tabs_admin-packages'}
    {tab name="{tr}Packages Installed{/tr}"}
        <br />
        <h4>{tr}Composer Packages{/tr} <small>{tr}Status of the packages registered in the composer.json file{/tr}</small></h4>
        <table class="table">
            <tr>
                <th>{tr}Package Name{/tr}</th>
                <th>{tr}Version Required{/tr}</th>
                <th>{tr}Status{/tr}
                <th>{tr}Version Installed{/tr}</th>
                <th>{tr}Action{/tr}</th>
            </tr>
            {foreach item=entry from=$composer_packages_installed}
                <tr>
                    <td>{$entry.name}</td>
                    <td>{$entry.required} {if $entry.upgradeVersion}<span class="label label-warning">{tr}Update:{/tr} {$entry.requiredVersion}</span>{/if}</td>
                    <td>
                        {if $entry.status == 'installed'}
                            {icon name='success' iclass='tips' ititle="{tr}Status{/tr}:{tr}Installed{/tr}"}
                        {elseif $entry.status == 'missing'}
                            {icon name='warning' iclass='tips' ititle="{tr}Status{/tr}:{tr}Missing{/tr}"}
                        {else}
                            &nbsp;
                        {/if}
                    </td>
                    <td>{$entry.installed|default:'&nbsp;'}</td>
                    <td>
                        {if $entry.key}
                            <form action="tiki-admin.php?page=packages&cookietab=1" method="post">
                                <input type="hidden" name="redirect" value="0">
                                <input type="hidden" name="installed" value="{$entry.installed}">
                                {ticket}
                                {if $entry.installed && $entry.upgradeVersion}
                                    <button class="btn btn-primary" name="auto-update-package" value="{$entry.key}">{tr}Update{/tr}</button>
                                {/if}
                                <button class="btn btn-danger" name="auto-remove-package" value="{$entry.key}">{tr}Remove{/tr}</button>
                            </form>
                        {/if}
                    </td>
                </tr>
            {/foreach}
            {if $composer_packages_missing}
                <tr>
                    <td colspan="5">
                        <h4>{tr}One or more packages appear to be missing{/tr}</h4>
                        {tr}In the list above, some packages could not be found. They are defined in the composer.json file, but do not seem to be installed.{/tr}

                        <br><br>

                        <h4>{tr}Install packages from the administrator interface{/tr}</h4>
                        {if $composer_available}
                            <p>
                            {tr}The administrator interface can be used to install the packages marked as missing in the list above.{/tr}
                            {tr}Click the "Fix Missing Packages" button below, and Tiki will try to install them{/tr}:
                            </p>
                            <form action="tiki-admin.php?page=packages&cookietab=1" method="post">
                                <input type="hidden" name="redirect" value="0">
                                {ticket}
                                <button class="btn btn-primary" name="auto-fix-missing-packages" value="auto-fix-missing-packages">{tr}Fix Missing Packages{/tr}</button>
                            </form>
                            <br />
                            The results of the execution of the commands will be displayed after the process finishes.
                        {else}
                            {tr}Composer was not detected. Please follow the manual instructions.{/tr}
                        {/if}

                        <br><br>

                        <h4>{tr}Install packages manually{/tr}</h4>
                        <p><strong>{tr}Make sure <code>composer</code> is installed.{/tr}</strong></p>
                        <p>
                            {tr}Composer can be installed manually, on the host machine, by following the instructions at the{/tr}
                            <a href="https://doc.tiki.org/Composer">Composer</a> {tr}website.{/tr}
                        </p>
                        <p>
                            {tr}The script <code>setup.sh</code> that is included in the Tiki distribution can be run to make sure composer is installed. In this case, composer will be installed as <code>temp/composer.phar</code>.{/tr}
                            {tr}Below is an example of how to do this in a Linux-like operating system:{/tr} <br>
                            <code>bash ./setup.sh composer</code>
                        </p>

                        <p><strong>{tr}Install the missing packages.{/tr}</strong></p>
                        <p>
                            {tr}After composer is installed, you can install the missing packages by issuing the command{/tr}
                            <code>composer --no-dev --prefer-dist update nothing</code>.
                            {tr}Below is an example of how to do this in a Linux-like operating system:{/tr} <br>
                            <code>php temp/composer.phar --no-dev --prefer-dist update nothing</code>
                        </p>
                    </td>
                </tr>
            {/if}
        </table>
    {/tab}
    {tab name="{tr}Install Other Packages{/tr}"}
        <br />
        <h4>{tr}Composer Packages{/tr} <small>{tr}These packages have been identified as required by one or more features.{/tr}</small></h4>
        <table class="table">
            <tr>
                <th>{tr}Package Name{/tr}</th>
                <th>{tr}Version{/tr}</th>
                <th>{tr}Licence{/tr}</th>
                <th>{tr}Required by{/tr}</th>
                <th>{tr}Action{/tr}</th>
            </tr>
            {foreach item=entry from=$composer_packages_available}
                <tr>
                    <td>{$entry.name}</td>
                    <td>{$entry.requiredVersion}</td>
                    <td><a href="{$entry.licenceUrl}">{if empty($entry.licence)}{tr}Not Available{/tr}{else}{$entry.licence}{/if}</a></td>
                    <td>{', '|implode:$entry.requiredBy}</td>
                    <td>
                        <form action="tiki-admin.php?page=packages&cookietab=2" method="post">
                            <input type="hidden" name="redirect" value="0">
                            {ticket}
                            <button class="btn btn-primary" name="auto-install-package" value="{$entry.key}">{tr}Install{/tr}</button>
                        </form>
                    </td>
                </tr>
            {/foreach}
            {if count($composer_packages_available)}
                <tr>
                    <td colspan="5">
                        <h4>{tr}There appear to be some optional packages that can be installed{/tr}</h4>
                        {tr}In the list above, there are optional packages that may be installed in order to use the Tiki features that require the package.{/tr}

                        <br><br>

                        <h4>{tr}Install packages from the administrator interface{/tr}</h4>
                        {if $composer_available}
                            {tr}The administrator interface can be used to install the optional packages in the list above.{/tr}
                            {tr}Click the "Install Package" button, and Tiki will try to install them.{/tr}
                        {else}
                            {tr}Composer was not detected. Please follow the manual instructions.{/tr}
                        {/if}

                        <br><br>

                        <h4>{tr}Install packages manually{/tr}</h4>
                        <p><strong>{tr}Make sure <code>composer</code> is installed.{/tr}</strong></p>
                        <p>
                            {tr}Composer can be installed manually, in the host machine, by following the instructions from the{/tr}
                            <a href="https://doc.tiki.org/Composer">Composer</a> {tr}website.{/tr}
                        </p>
                        <p>
                            {tr}The script <code>setup.sh</code> that is included in the Tiki distribution can be run to make sure composer is installed. In this case, composer will be installed as <code>temp/composer.phar</code>.{/tr}
                            {tr}Below is an example of how to do this in a Linux-like operating system:{/tr}<br>
                            <code>bash ./setup.sh composer</code>
                        </p><br>

                        <p><strong>{tr}Make sure there is a <code>composer.json</code> file in the root of the website.{/tr}</strong></p>
                        <p>
                            {tr}If there is not already a <code>composer.json</code> file, then create one.{/tr}
                            {tr}The sample <code>composer.json.dist</code> that comes with Tiki can be used as a starting point.{/tr}
                            {tr}Below is an example of how to do this in a Linux-like operating system:{/tr} <br>
                            <code>cp composer.json.dist composer.json</code>
                        </p><br>

                        <p><strong>{tr}Install the package.{/tr}</strong></p>
                        <p>
                            {tr}After all the steps above (that only need to be performed once), packages can be installed by issuing a command{/tr}
                            <code>composer require package:version</code> {tr}for each package that is to be installed.{/tr}
                            {tr}Below is an example of how to do this in a Linux-like operating system:{/tr} <br>
                            <code>php temp/composer.phar require --update-no-dev --prefer-dist psr/log:^1.0</code>
                        </p>
                    </td>
                </tr>
            {/if}
        </table>
    {/tab}
    {tab name="{tr}Packages Custom{/tr}"}
        <br>
        <h4>{tr}Composer Packages Custom <small>Status of the packages registered in the vendor_custom folder</small>{/tr}</h4>
        {if $composer_custom_packages_installed}
            {remarksbox type="info" title="{tr}For information only{/tr}"}
            {tr}These packages are managed manually and displayed here for informational purposes.{/tr}
            {/remarksbox}
            {foreach item=packages key=folderName from=$composer_custom_packages_installed}
                <h5 style="margin-top: 20px">{$folderName}</h5>
                <table class="table">
                    <tr>
                        <th>{tr}Package Name{/tr}</th>
                        <th>{tr}Version Required{/tr}</th>
                        <th>{tr}Status{/tr}
                        <th>{tr}Version Installed{/tr}</th>
                    </tr>
                    {foreach item=package from=$packages}
                        <tr>
                        <td width="50%">{$package.name}</td>
                        <td width="20%">{$package.required}</td>
                        <td width="10%">
                            {if $package.status == 'installed'}
                                {icon name='success' iclass='tips' ititle="{tr}Status{/tr}:{tr}Installed{/tr}"}
                            {elseif $package.status == 'missing'}
                                {icon name='warning' iclass='tips' ititle="{tr}Status{/tr}:{tr}Missing{/tr}"}
                            {else}
                                &nbsp;
                            {/if}
                            </td>
                            <td width="20%">{$package.installed|default:'&nbsp;'}</td>
                        </tr>
                    {/foreach}
                </table>
            {/foreach}
        {else}
            {remarksbox type="info" title="{tr}There are no manual managed packages installed{/tr}"}
            {tr}Please check <a href="https://packages.tiki.org/">Tiki Packages</a>.{/tr}
            {/remarksbox}
        {/if}
    {/tab}
    {tab name="{tr}Packages Bundled{/tr}"}
        <br>
        <h4>{tr}Composer Packages Bundled{/tr} <small>{tr}status of the packages registered in the vendor_bundled/composer.json file{/tr}</small></h4>
        {if $composer_available}
            {remarksbox type="info" title="{tr}For information only{/tr}"}
            {tr}These packages are bundled with Tiki, and displayed here for informational purposes.{/tr}
            {/remarksbox}
        {/if}
        <table class="table">
            <tr>
                <th>{tr}Package Name{/tr}</th>
                <th>{tr}Version Required{/tr}</th>
                <th>{tr}Status{/tr}
                <th>{tr}Version Installed{/tr}</th>
            </tr>
            {foreach item=entry from=$composer_bundled_packages_installed}
                <tr>
                    <td>{$entry.name}</td>
                    <td>{$entry.required}</td>
                    <td>
                        {if $entry.status == 'installed'}
                            {icon name='success' iclass='tips' ititle="{tr}Status{/tr}:{tr}Installed{/tr}"}
                        {elseif $entry.status == 'missing'}
                            {icon name='warning' iclass='tips' ititle="{tr}Status{/tr}:{tr}Missing{/tr}"}
                        {else}
                            &nbsp;
                        {/if}
                    </td>
                    <td>{$entry.installed|default:'&nbsp;'}</td>
                </tr>
            {/foreach}
        </table>
    {/tab}
    {tab name="{tr}Diagnose{/tr}"}
        <br>
        <h4>{tr}Diagnose Composer Installation{/tr} <small>{tr}Use the button below to test your composer installation.{/tr}</small></h4>
        <form action="tiki-admin.php?page=packages&cookietab=5" method="post">
            <input type="hidden" name="redirect" value="0">
            {ticket}
            <button class="btn btn-primary" name="auto-run-diagnostics" value="run">{tr}Diagnose Composer{/tr}</button>
        </form>
        <br />
        <h4>{tr}Composer management{/tr}</h4>
        <form action="tiki-admin.php?page=packages&cookietab=5" method="post">
            <input type="hidden" name="redirect" value="0">
            {ticket}
            {if ! $composer_available}
                <button class="btn btn-primary" name="install-composer" value="run">{tr}Install Composer{/tr}</button>
            {else}
                <button class="btn btn-primary" name="update-composer" value="run">{tr}Update Composer{/tr}</button>
            {/if}
            <button class="btn btn-primary" name="remove-composer-locker" value="run">{tr}Remove composer.lock{/tr}</button>
            <button class="btn btn-primary" name="clean-vendor-folder" value="run">{tr}Clean vendor folder{/tr}</button>
        </form>
        <br />

        {if isset($diagnostic_composer_location) || $diagnostic_composer_output || $composer_management_success || $composer_management_error}
            <br />
            <h4>{tr}Results{/tr}</h4>
            {if isset($diagnostic_composer_location) }
                <p><strong>Composer:</strong> {if $diagnostic_composer_location}{tr}{$diagnostic_composer_location}{/tr}{else}{tr}Composer not found{/tr}{/if}</p>
            {/if}
            {if $diagnostic_composer_output}
                <p><strong>Composer diagnose output</strong></p>
                <pre>{$diagnostic_composer_output}</pre>
            {/if}

            {if $composer_management_success}
                {remarksbox type="success" title="{tr}Success{/tr}" close="n"}
                {$composer_management_success}
                {/remarksbox}
            {/if}

            {if $composer_management_error}
                {remarksbox type="error" title="{tr}Error{/tr}" close="n"}
                {$composer_management_error}
                {/remarksbox}
            {/if}
            <br>
        {/if}
    {/tab}
{/tabset}

{jq}
	$(document).ready(function(){
		$(".install-composer").click(function(){
			$('.nav-tabs a[href="#contenttabs_admin_packages-5"]').tab('show');
		});
	});
{/jq}
