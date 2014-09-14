set :stages,        %w(staging nodejs production nodejs_production)
set :default_stage, "staging"
set :stage_dir,     "app/config"

require 'capistrano/ext/multistage'

set :application, "SD"
#set :domain,      "gate.jelastic.neohost.net"
#set :domain,      "91.223.223.238"
#set :deploy_to,   "/var/www/vhosts/itdoors.com.ua/subdomains/deploy_sd"
set :deploy_to,   "/var/www/webroot/ci"
set :app_path,    "app"

set :repository,  "git@github.com:itdoors/sd.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

#git
#set :branch, "staging"
#set :branch, "task-multideploy"
set :git_enable_submodules, 1

set :model_manager, "doctrine"
# Or: `propel`

set :keep_releases,  2
set :use_sudo,      false
#set :user,       "root"
#set :user,       "23849-5766"

ssh_options[:keys] = [File.join(ENV["HOME"], ".ssh", "id_rsa")]
#ssh_options[:port] = 3764
ssh_options[:port] = 3022
ssh_options[:forward_agent] = true

default_run_options[:pty] = true

set :shared_files,        ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", "node/node_modules", app_path + "/share", web_path + "/files"]

#composer
set :use_composer,        true
set :copy_vendors,        true
set :update_vendors,      true

set :cache_warmup,        false
set :webserver_user,      "nginx"

before 'symfony:composer:install', 'composer:copy_vendors'
before 'symfony:composer:update', 'composer:copy_vendors'

namespace :composer do
  task :copy_vendors, :except => { :no_release => true } do
    capifony_pretty_print "--> Copy vendor file from previous release"

    run "vendorDir=#{current_path}/vendor; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir #{latest_release}/vendor; fi;"
    capifony_puts_ok
  end
end

set :parameters_dir, "app/config/parameters"
set :parameters_file, false

task :upload_parameters do
  origin_file = parameters_dir + "/" + parameters_file if parameters_dir && parameters_file
  if origin_file && File.exists?(origin_file)
    ext = File.extname(parameters_file)
    relative_path = "app/config/parameters" + ext

    if shared_files && shared_files.include?(relative_path)
      destination_file = shared_path + "/" + relative_path
    else
      destination_file = latest_release + "/" + relative_path
    end
    try_sudo "mkdir -p #{File.dirname(destination_file)}"

    top.upload(origin_file, destination_file)
  end
end

before 'deploy', 'upload_parameters'

after "deploy:update_code" do
  capifony_pretty_print "--> Ensuring cache directory permissions"
  run "chmod 777 -R #{latest_release}/#{cache_path}"
#  run "setfacl -R -m u:nginx:rwX -m u:`whoami`:rwX #{latest_release}/#{cache_path}"
#  run "setfacl -dR -m u:nginx:rwX -m u:`whoami`:rwX #{latest_release}/#{cache_path}"
  capifony_puts_ok
  symfony.cache.warmup
  capifony_puts_ok
end

#after "deploy", "deploy:cleanup"
# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL