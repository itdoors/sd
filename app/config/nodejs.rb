server "gate.jelastic.neohost.net", :web, :app, :primary => true
set :user,       "23918-5766"
set :parameters_file, "parameters_nodejs.yml"

set :deploy_via, :remote_cache

#role :web,        domain                          # Your HTTP server, Apache/etc
#role :app,        domain, :primary => true       # This may be the same as your `Web` server

after "deploy",  "deploy:cleanup"