server "193.242.166.21", :web, :app, :primary => true
set :user,        "23982-5766"
set :parameters_file, "parameters_staging.yml"
set :branch, "master"

after "deploy",  "deploy:cleanup"