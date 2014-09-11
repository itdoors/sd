server "gate.jelastic.neohost.net", :web, :app, :primary => true
set :user,       "23918-5766"
set :parameters_file, "parameters_nodejs.yml"

set :deploy_via, :remote_cache

#role :web,        domain                          # Your HTTP server, Apache/etc
#role :app,        domain, :primary => true       # This may be the same as your `Web` server

desc "Install node modules non-globally"
task :npm_update do
    run "cd #{latest_release}/node/ami && npm -g update"
end

namespace :forever do
    task :stop do
        run "sudo forever stopall"
    end

    task :start do
        run "cd #{latest_release}/node/ami && sudo forever start master.js"
    end

    task :restart do
        forever_stop
        sleep 5
        forever_start
    end
end

after "deploy",  "deploy:cleanup"
after "deploy",  "npm_update"
after "deploy",  "forever:restart"