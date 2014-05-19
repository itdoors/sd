set :application, "SD"
set :domain,      "91.223.223.238"
set :deploy_to,   "/var/www/vhosts/itdoors.com.ua/subdomains/deploy_sd"
set :app_path,    "app"

set :repository,  "git@github.com:itdoors/sd.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set :keep_releases,  3
set :use_sudo,      false
set :user,       "root"
set :git_enable_submodules, 1
ssh_options[:keys] = [File.join(ENV["HOME"], ".ssh", "id_dsa")]
ssh_options[:port] = 3764
ssh_options[:forward_agent] = true

default_run_options[:pty] = true

set :shared_files,        ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads"]

set :cache_warmup,        false
set :use_composer,        true

after "deploy:update_code" do
  capifony_pretty_print "--> Ensuring cache directory permissions"
  run "setfacl -R -m u:\"nginx\":rwX -m u:`whoami`:rwX #{latest_release}/#{cache_path}"
  run "setfacl -dR -m u:\"nginx\":rwX -m u:`whoami`:rwX #{latest_release}/#{cache_path}"
  capifony_puts_ok
end

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL