begin
  require 'rspec/core/rake_task'
  RSpec::Core::RakeTask.new(:spec) do |t|
    t.verbose = false
  end
rescue LoadError
  puts 'RSpec is not installed'
end
