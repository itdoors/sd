server "193.242.166.21", :web, :app, :primary => true
set :user,       "23918-5766"
set :parameters_file, "parameters_nodejs.yml"
set :branch, "staging"

set :deploy_via, :remote_cache

#role :web,        domain                          # Your HTTP server, Apache/etc
#role :app,        domain, :primary => true       # This may be the same as your `Web` server

desc "Install node modules non-globally"
task :npm_update do
    run "cd #{latest_release}/node && npm install"
end

namespace :forever do
    task :stop do
        capifony_pretty_print "--> stop node.js"
        run "sudo forever stopall; true"
    end

    task :start do
        capifony_pretty_print "--> start node.js"
        run "cd #{latest_release}/node/ami && sudo forever start master.js"
    end

    task :restart do
        capifony_pretty_print "--> restart node.js"
        stop
        sleep 5
        start
        capifony_puts_ok
    end
end

after "deploy",  "deploy:cleanup"
after "deploy:update_code",  "npm_update"
after "deploy:update_code",  "forever:restart"