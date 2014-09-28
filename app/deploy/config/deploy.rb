# config valid only for Capistrano 3.1

set :application, 'Fairytale'
set :repo_url, 'git@github.com:nfqakademija/fairytale.git'


set :linked_files, %w{app/config/parameters.yml}
set :linked_dirs, %w{app/logs app/cache vendor web/media web/uploads}
set :keep_releases, 5

namespace :deploy do

  before :publishing, :restart

  before :restart, :clear_cache do
    on roles(:web) do
        execute "cd #{release_path} && composer install --prefer-dist"
        execute "cd #{release_path} && app/console assets:install"
        execute "cd #{release_path} && app/console assetic:dump"
    end
  end
  after :finishing, 'deploy:cleanup'
end

