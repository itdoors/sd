set :application, "SD"
set :domain,      "gate.jelastic.neohost.net"
#set :domain,      "91.223.223.238"
#set :deploy_to,   "/var/www/vhosts/itdoors.com.ua/subdomains/deploy_sd"
set :deploy_to,   "/var/www/webroot/ci"
set :app_path,    "app"

set :repository,  "git@github.com:itdoors/sd.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

#git
set :branch, "staging"
set :git_enable_submodules, 1

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set :keep_releases,  2
set :use_sudo,      false
#set :user,       "root"
set :user,       "23849-5766"

ssh_options[:keys] = [File.join(ENV["HOME"], ".ssh", "id_dsa")]
#ssh_options[:port] = 3764
ssh_options[:port] = 3022
ssh_options[:forward_agent] = true

default_run_options[:pty] = true

set :shared_files,        ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor", app_path + "/share", web_path + "/files"]

#composer
set :use_composer,        true
set :update_vendors,      true

set :cache_warmup,        false
set :webserver_user,      "nginx"

after "deploy:update_code" do
  capifony_pretty_print "--> Ensuring cache directory permissions"
  run "chmod 777 -R #{latest_release}/#{cache_path}"
#  run "setfacl -R -m u:nginx:rwX -m u:`whoami`:rwX #{latest_release}/#{cache_path}"
#  run "setfacl -dR -m u:nginx:rwX -m u:`whoami`:rwX #{latest_release}/#{cache_path}"
  capifony_puts_ok
  symfony.cache.warmup
  capifony_puts_ok
end

after "deploy", "deploy:cleanup"
# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL